package com.samsung.tablepresentation;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.util.Log;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

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

public class UpdateBasicFilesTask extends AsyncTask<String, Void, Void> {

    private static final String TAG = UpdateBasicFilesTask.class.getSimpleName();

    private String username;
    private String password;
    private Context context;
    private Activity mainActivity;

    public UpdateBasicFilesTask(String username, String password, Context context, Activity mainActivity) {
        this.context = context;
        this.username = username;
        this.password = password;
        this.mainActivity = mainActivity;
    }

    @Override
    protected Void doInBackground(String... params) {
        String baseUrl = params[0];
        String versionFolder = params[1];
        String versionGuid = params[2];

        try {
            URL url = new URL(baseUrl +"/basicFiles.zip");
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
                File folder = new File(appDirectory, "");
                if (!folder.exists()) {
                    folder.mkdir();
                }

                // Create a destination file for the ZIP file
                File destinationFile = new File(folder, "basicFiles.zip");

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

    public void restartApp() {
        if (mainActivity != null) {
            Intent intent = mainActivity.getIntent();
            mainActivity.finish();
            mainActivity.startActivity(intent);
        }
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
}
