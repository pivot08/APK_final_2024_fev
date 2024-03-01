package com.samsung.tablepresentation;

import android.os.AsyncTask;
import android.util.Log;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Base64;
import java.util.zip.ZipEntry;
import java.util.zip.ZipInputStream;
import android.content.Context;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class UpdateLocalVersionTask extends AsyncTask<String, Void, Void> {

    private static final String TAG = UpdateLocalVersionTask.class.getSimpleName();

    private String username;
    private String password;
    private Context context;

    public UpdateLocalVersionTask(String username, String password, Context context) {
        this.context = context;
        this.username = username;
        this.password = password;
    }

    @Override
    protected Void doInBackground(String... params) {
        String baseUrl = params[0];
        String versionFolder = params[1];
        String versionGuid = params[2];

        try {
            URL url = new URL(baseUrl +"/"+ versionFolder +"/"+ versionGuid +".zip");
            HttpURLConnection urlConnection = (HttpURLConnection) url.openConnection();

             // Set the authorization header
            String authString = username + ":" + password;
            String authHeaderValue = "Basic " + Base64.getEncoder().encodeToString(authString.getBytes());
            urlConnection.setRequestProperty("Authorization", authHeaderValue);

            // Connect to the server
            urlConnection.connect();

            // Check if the response code is successful
            int responseCode = urlConnection.getResponseCode();
            if (responseCode == HttpURLConnection.HTTP_OK) {
                // Get the input stream from the connection
                InputStream inputStream = new BufferedInputStream(urlConnection.getInputStream());

                //get app folder
                File appDirectory = context.getFilesDir();

                //create folder if not exists
                File folder = new File(appDirectory, versionGuid);
                if (!folder.exists()) {
                    folder.mkdir();
                }

                // Create a destination file for the ZIP file
                File destinationFile = new File(folder, versionGuid +".zip");

                // Create an output stream for the destination file
                FileOutputStream outputStream = new FileOutputStream(destinationFile);

                // Read and write the contents of the ZIP file
                byte[] buffer = new byte[1024];
                int bytesRead;
                while ((bytesRead = inputStream.read(buffer)) != -1) {
                    outputStream.write(buffer, 0, bytesRead);
                }

                // Close the streams
                inputStream.close();
                outputStream.close();

                // Extract the contents of the ZIP file
                extractZipFile(destinationFile, String.valueOf(folder));

                // Delete the ZIP file after extraction if needed
                destinationFile.delete();

                //save a local json file with version guid
                if (versionGuid != "operator") {
                    JSONObject jsonContent = new JSONObject();
                    try {
                        jsonContent.put("version", versionGuid);
                        writeJsonToFile(context, jsonContent, "version");
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }

                    MainActivity.informDevice();
                }
            } else {
                Log.e(TAG, "Failed to download file. HTTP response code: " + responseCode);
            }

            // Disconnect the connection
            urlConnection.disconnect();

        } catch (IOException e) {
            e.printStackTrace();
        }

        return null;
    }

    private void extractZipFile(File zipFile, String destinationFolder) {
        try {
            // Create input streams
            ZipInputStream zipInputStream = new ZipInputStream(new BufferedInputStream(new FileInputStream(zipFile)));

            // Loop through all entries in the ZIP file
            ZipEntry entry;
            while ((entry = zipInputStream.getNextEntry()) != null) {
                // Create destination file
                File destinationFile = new File(destinationFolder, entry.getName());

                // Create parent directories if they don't exist
                if (!destinationFile.getParentFile().exists()) {
                    destinationFile.getParentFile().mkdirs();
                }

                // Write the entry to the destination file
                FileOutputStream fileOutputStream = new FileOutputStream(destinationFile);
                byte[] buffer = new byte[1024];
                int bytesRead;
                while ((bytesRead = zipInputStream.read(buffer)) != -1) {
                    fileOutputStream.write(buffer, 0, bytesRead);
                }
                fileOutputStream.close();
            }

            // Close the ZIP file input stream
            zipInputStream.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void writeJsonToFile(Context context, JSONObject jsonObject, String fileName) {
        try {
            // Convert the JSON object to a string
            String jsonString = jsonObject.toString();

            // Open a file output stream
            FileOutputStream fileOutputStream = context.openFileOutput(fileName +".json", Context.MODE_PRIVATE);

            // Write the string to the file
            fileOutputStream.write(jsonString.getBytes());

            // Close the file output stream
            fileOutputStream.close();

        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void writeJsonArrayToFile(Context context, JSONArray jsonArray, String fileName) {
        try {
            // Convert JSONArray to a formatted JSON string
            String jsonString = jsonArray.toString(4);

            // Open the file for writing
            FileOutputStream fileOutputStream = context.openFileOutput(fileName +".json", Context.MODE_PRIVATE);
            OutputStreamWriter outputStreamWriter = new OutputStreamWriter(fileOutputStream);

            // Write the JSON string to the file
            outputStreamWriter.write(jsonString);

            // Close the streams
            outputStreamWriter.close();
            fileOutputStream.close();

            Log.d(TAG, "JSONArray written to file: " + fileName);
        } catch (IOException | JSONException e) {
            Log.e(TAG, "Error writing JSONArray to file", e);
        }
    }

    public static void writeJsonStringToFile(Context context, String jsonString, String fileName) {
        try {
            // Open the file for writing
            FileOutputStream fileOutputStream = context.openFileOutput(fileName +".json", Context.MODE_PRIVATE);
            OutputStreamWriter outputStreamWriter = new OutputStreamWriter(fileOutputStream);

            // Write the JSON string to the file
            outputStreamWriter.write(jsonString);

            // Close the streams
            outputStreamWriter.close();
            fileOutputStream.close();

            Log.d(TAG, "JSONArray written to file: " + fileName);
        } catch (IOException e) {
            Log.e(TAG, "Error writing JSONArray to file", e);
        }
    }

    public static JSONObject readJsonFromFile(Context context, String fileName) {
        try {
            // Open a file input stream
            FileInputStream fileInputStream = context.openFileInput(fileName);

            // Read the contents of the file using BufferedReader
            BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(fileInputStream));
            StringBuilder stringBuilder = new StringBuilder();
            String line;

            while ((line = bufferedReader.readLine()) != null) {
                stringBuilder.append(line).append('\n');
            }

            // Close the streams
            bufferedReader.close();
            fileInputStream.close();

            // Convert the string to a JSON object
            String jsonString = stringBuilder.toString();
            return new JSONObject(jsonString);

        } catch (IOException | JSONException e) {
            e.printStackTrace();
            // Handle the exception
        }

        return null; // Return null if reading fails
    }
}
