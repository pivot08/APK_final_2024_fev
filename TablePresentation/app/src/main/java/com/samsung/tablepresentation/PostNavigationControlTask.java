package com.samsung.tablepresentation;

import android.content.Context;
import android.os.AsyncTask;
import android.os.Build;
import android.provider.Settings;
import android.util.Log;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Base64;

public class PostDeviceInfoTask extends AsyncTask<String, Void, String> {

    private static final String TAG = PostDeviceInfoTask.class.getSimpleName();

    private String username;
    private String password;
    private Context context;

    public PostDeviceInfoTask(String username, String password, Context context) {
        this.context = context;
        this.username = username;
        this.password = password;
    }

    @Override
    protected String doInBackground(String... params) {
        if (params.length == 0) {
            return null;
        }

        String url = params[0];
        String postData = buildPostData(params[1]);

        try {
            URL apiUrl = new URL(url);
            HttpURLConnection connection = (HttpURLConnection) apiUrl.openConnection();

            // Set the authorization header
            String authString = username + ":" + password;
            String authHeaderValue = "Basic " + Base64.getEncoder().encodeToString(authString.getBytes());
            connection.setRequestProperty("Authorization", authHeaderValue);

            // Set the request method to POST
            connection.setRequestMethod("POST");

            // Enable input/output streams
            connection.setDoInput(true);
            connection.setDoOutput(true);

            // Set content type
            connection.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");

            // Write the data to the output stream
            DataOutputStream dataOutputStream = new DataOutputStream(connection.getOutputStream());
            dataOutputStream.writeBytes(postData);
            dataOutputStream.flush();
            dataOutputStream.close();

            // Get the response code
            int responseCode = connection.getResponseCode();

            // Read the response
            BufferedReader bufferedReader;
            if (responseCode == HttpURLConnection.HTTP_OK) {
                bufferedReader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
            } else {
                bufferedReader = new BufferedReader(new InputStreamReader(connection.getErrorStream()));
            }

            // Read the response data
            StringBuilder response = new StringBuilder();
            String line;
            while ((line = bufferedReader.readLine()) != null) {
                response.append(line);
            }
            bufferedReader.close();

            // Close the connection
            connection.disconnect();

            return response.toString();
        } catch (IOException e) {
            Log.e(TAG, "Error during POST request", e);
            return null;
        }
    }

    @Override
    protected void onPostExecute(String result) {
        // Handle the result in the UI thread
        if (result != null) {
            Log.d(TAG, "POST request result: " + result);
        } else {
            Log.e(TAG, "POST request failed");
        }
    }

    private String buildPostData(String versionGuid) {
        // Customize the data you want to send in the POST request
        String deviceId = getDeviceId();
        String deviceModel = Build.MODEL;
        String deviceManufacturer = Build.MANUFACTURER;

        // Build the POST data
        return "version="+ versionGuid +
                "&deviceId=" + deviceId +
                "&deviceModel=" + deviceModel +
                "&deviceManufacturer=" + deviceManufacturer;
    }

    private String getDeviceId() {
        // Use a combination of device-specific identifiers to obtain a unique identifier
        // This example uses the Android ID
        String androidId = Settings.Secure.getString(context.getContentResolver(), Settings.Secure.ANDROID_ID);
        return androidId != null ? androidId : "";
    }
}
