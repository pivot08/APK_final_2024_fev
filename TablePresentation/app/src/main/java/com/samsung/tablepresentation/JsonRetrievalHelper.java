package com.samsung.tablepresentation;

import static com.samsung.tablepresentation.UpdateLocalVersionTask.writeJsonArrayToFile;
import static com.samsung.tablepresentation.UpdateLocalVersionTask.writeJsonStringToFile;
import static com.samsung.tablepresentation.UpdateLocalVersionTask.writeJsonToFile;

import android.content.Context;
import android.os.AsyncTask;
import android.util.Log;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Base64;

public class JsonRetrievalHelper extends AsyncTask<String, Void, String> {

    private static final String TAG = JsonRetrievalHelper.class.getSimpleName();
    private static String username;
    private static String password;
    private static String actualVersion;
    private Context context;
    private String contentType;
    private String fileName;

    public JsonRetrievalHelper(Context context, String username, String password, String actualVersion, String fileName) {
        this.context = context;
        this.username = username;
        this.password = password;
        this.actualVersion = actualVersion;
        this.fileName = fileName;
    }

    protected String doInBackground(String... urls) {
        if (urls.length == 0) {
            return null;
        }

        String urlString = urls[0];
        String contentType = urls[1];
        this.contentType = contentType;
        try {
            URL url = new URL(urlString);
            HttpURLConnection urlConnection = (HttpURLConnection) url.openConnection();

            try {
                String authString = username + ":" + password;
                String authHeaderValue = "Basic " + Base64.getEncoder().encodeToString(authString.getBytes());
                urlConnection.setRequestProperty("Authorization", authHeaderValue);

                // Connect to the server
                urlConnection.connect();

                // Check if the response code is successful
                int responseCode = urlConnection.getResponseCode();
                if (responseCode == HttpURLConnection.HTTP_OK) {
                    BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(urlConnection.getInputStream()));
                    StringBuilder stringBuilder = new StringBuilder();

                    String line;
                    while ((line = bufferedReader.readLine()) != null) {
                        stringBuilder.append(line).append("\n");
                    }

                    return stringBuilder.toString();
                }
            } finally {
                urlConnection.disconnect();
            }
        } catch (IOException e) {
            Log.e(TAG, "Error retrieving JSON from URL", e);
            return null;
        }
        return null;
    }

    @Override
    protected void onPostExecute(String jsonResult) {
        // Process the JSON result here
        if (jsonResult != null) {
            // Parse and handle the JSON data
            try {
                if (this.contentType == "1") {
                    JSONObject jsonObject = new JSONObject(jsonResult);
                    String value = jsonObject.getString("version");
                    MainActivity.processContentAttualization(value);
                }

                if (this.contentType == "2") {
                    JSONArray jsonArray = new JSONArray(jsonResult);
                    writeJsonArrayToFile(context, jsonArray, this.actualVersion);
                    MainActivity.processVersionAttualization();
                }

                if (this.contentType == "3") {
                    JSONArray jsonArray = new JSONArray(jsonResult);
                    writeJsonArrayToFile(context, jsonArray, this.fileName.replace(".json", ""));
                    MainActivity.processOperatorFileAttualization();
                }

                if (this.contentType == "4") {
                    writeJsonStringToFile(context, jsonResult, this.fileName.replace(".json", ""));
                }
            } catch (JSONException e) {
                Log.e(TAG, "Error parsing JSON", e);
            }
        } else {
            Log.e(TAG, "JSON Result is null");
        }
    }
}
