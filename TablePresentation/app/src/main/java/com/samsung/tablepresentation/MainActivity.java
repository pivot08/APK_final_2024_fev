package com.samsung.tablepresentation;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import androidx.appcompat.app.AppCompatActivity;

import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.widget.Toast;

import org.json.JSONObject;

public class MainActivity extends AppCompatActivity {

    static WebView webView;
    private static String baseUrl = "https://dpopinterativo.dev.br";
    private static String path = "file:///data/data/com.samsung.tablepresentation/files/";
    private static String versionFolder = "version";
    private static String localVersion = "";
    private static String actualVersion = "";
    private static String username = "umbler";
    private static String password = "testehospedagem";

    public static Context context;
    private static UpdateLocalVersionTask updateLocalVersion;


    @SuppressLint("JavascriptInterface")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        context = this;

        webView = findViewById(R.id.contentWebView);

        WebSettings webSettings = webView.getSettings();
        webSettings.setBuiltInZoomControls(true);
        webSettings.setDisplayZoomControls(false);
        webSettings.setJavaScriptEnabled(true);
        webSettings.setAllowFileAccess(true);
        webSettings.setAllowFileAccessFromFileURLs(true);
        webSettings.setAllowUniversalAccessFromFileURLs(true);

        if (isConnectedToInternet()) {
            String contentType = "1";
            JsonRetrievalHelper jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "");
            jsonRetrievalHelper.execute(baseUrl +"/version.json", contentType);

            //baixa os arquivos das operadoras
            contentType = "3";
            jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "user.json");
            jsonRetrievalHelper.execute(baseUrl +"/user.json", contentType);

            contentType = "4";
            jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "vivo.json");
            jsonRetrievalHelper.execute(baseUrl +"/vivo.json", contentType);

            jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "claro.json");
            jsonRetrievalHelper.execute(baseUrl +"/claro.json", contentType);

            jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "tim.json");
            jsonRetrievalHelper.execute(baseUrl +"/tim.json", contentType);
        }

        webView.setWebViewClient(new Callback());
        webView.setWebChromeClient(new WebChromeClient());

        String fileName = "version.json";
        JSONObject retrievedJson = UpdateLocalVersionTask.readJsonFromFile(this, fileName);

        if (retrievedJson != null) {
            localVersion = retrievedJson.optString("version", "");
            webView.loadUrl("file:///android_asset/index.html?path="+ path +"&actualVersion="+ localVersion);
        } else {
            webView.loadUrl("file:///android_asset/eureka/base/index.html");
        }

        updateLocalVersion = new UpdateLocalVersionTask(username, password, context, this);
    }

    private class Callback extends WebViewClient {
        @Override
        public boolean shouldOverrideUrlLoading(WebView view, String url) {
            return false;
        }
    }

    @Override
    public void onBackPressed() {
        if (webView != null && webView.canGoBack()) {
            webView.goBack();
            return;
        }

        super.onBackPressed();
    }

    private boolean isConnectedToInternet() {
        ConnectivityManager connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);

        if (connectivityManager != null) {
            NetworkInfo activeNetwork = connectivityManager.getActiveNetworkInfo();

            if (activeNetwork != null && activeNetwork.isConnected()) {
                return true;
            }
        }

        return false;
    }

    public static void processContentAttualization(String serverVersion) {
        if (serverVersion != null && serverVersion != "") {
            actualVersion = serverVersion;

            String contentType = "2";
            JsonRetrievalHelper jsonRetrievalHelper = new JsonRetrievalHelper(context, username, password, actualVersion, "");
            jsonRetrievalHelper.execute(baseUrl + "/" + actualVersion + ".json", contentType);
        }
    }

    public static void processVersionAttualization() {
        if (actualVersion != null) {
            try {
                if (actualVersion != "" && !actualVersion.toString().equals(localVersion.toString())) {
                    updateLocalVersion.execute(baseUrl, versionFolder, actualVersion);
                }
            } catch (Exception e) {

            }
        }
    }

    public static void processOperatorFileAttualization() {
        UpdateOperatorVersionTask updateOperatorVersion = new UpdateOperatorVersionTask(username, password, context);
        updateOperatorVersion.execute(baseUrl, versionFolder, "operator");
    }

    public static void informDevice() {
        String url = baseUrl +"/picms/func/device.php";
        PostDeviceInfoTask postDeviceInfoTask = new PostDeviceInfoTask(username, password, context);
        postDeviceInfoTask.execute(url, actualVersion);
    }
}