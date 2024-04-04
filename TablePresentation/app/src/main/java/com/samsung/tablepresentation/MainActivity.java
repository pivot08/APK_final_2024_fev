package com.samsung.tablepresentation;

import android.annotation.SuppressLint;
import android.content.Context;
import android.os.Bundle;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.webkit.JavascriptInterface;

import androidx.appcompat.app.AppCompatActivity;

import android.net.ConnectivityManager;
import android.net.NetworkInfo;

import org.json.JSONObject;

public class MainActivity extends AppCompatActivity {

    static WebView webView;
    static String baseUrl = "https://dpopinterativo.dev.br";
    private static String path = "file:///data/data/com.samsung.tablepresentation/files/";
    private static String versionFolder = "version";
    private static String localVersion = "";
    static String actualVersion = "";
    static String username = "umbler";
    static String password = "testehospedagem";

    public static Context context;
    private static UpdateLocalVersionTask updateLocalVersion;
    private static UpdateBasicFilesTask updateBasicFiles;


    @SuppressLint("SetJavaScriptEnabled")
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

        JSFunction jsFunction = new JSFunction(this);

        if (isConnectedToInternet()) {
            String contentType = "1";
            JsonRetrievalHelper jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "");
            jsonRetrievalHelper.execute(baseUrl +"/version/version.json", contentType);

            //baixa os arquivos das operadoras
            contentType = "3";
            jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "user.json");
            jsonRetrievalHelper.execute(baseUrl +"/version/user.json", contentType);

            contentType = "4";
            jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "vivo.json");
            jsonRetrievalHelper.execute(baseUrl +"/version/vivo.json", contentType);

            jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "claro.json");
            jsonRetrievalHelper.execute(baseUrl +"/version/claro.json", contentType);

            jsonRetrievalHelper = new JsonRetrievalHelper(this, username, password, actualVersion, "tim.json");
            jsonRetrievalHelper.execute(baseUrl +"/version/tim.json", contentType);
        }

        webView.setWebViewClient(new Callback());
        webView.addJavascriptInterface(jsFunction, "Android");
        webView.setWebChromeClient(new WebChromeClient());

        String fileName = "version.json";
        JSONObject retrievedJson = UpdateLocalVersionTask.readJsonFromFile(this, fileName);

        if (retrievedJson != null) {
            localVersion = retrievedJson.optString("version", "");
            webView.loadUrl(path +"index.html?tablet=1&path="+ path +"&actualVersion="+ localVersion);
        } else {
            webView.loadUrl("file:///android_asset/indice.html");
        }

        updateBasicFiles = new UpdateBasicFilesTask(username, password, context, this);
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

    boolean isConnectedToInternet() {
        ConnectivityManager connectivityManager = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);

        if (connectivityManager != null) {
            NetworkInfo activeNetwork = connectivityManager.getActiveNetworkInfo();

            if (activeNetwork != null && activeNetwork.isConnected()) {
                return true;
            }
        }

        return false;
    }

    public static void processContentUpdate(String serverVersion) {
        if (serverVersion != null && serverVersion != "") {
            actualVersion = serverVersion;

            String contentType = "2";
            JsonRetrievalHelper jsonRetrievalHelper = new JsonRetrievalHelper(context, username, password, actualVersion, "");
            jsonRetrievalHelper.execute(baseUrl + "/version/" + actualVersion + ".json", contentType);
        }
    }

    public static void processVersionUpdate() {
        if (actualVersion != null) {
            try {
                if (actualVersion != "" && !actualVersion.toString().equals(localVersion.toString())) {
                    updateLocalVersion.execute(baseUrl, versionFolder, actualVersion);
                }
            } catch (Exception e) {

            }
        }
    }

    public static void processOperatorFileUpdate() {
        UpdateOperatorVersionTask updateOperatorVersion = new UpdateOperatorVersionTask(username, password, context);
        updateOperatorVersion.execute(baseUrl, versionFolder, "operator");
    }

    public static void informDevice() {
        updateBasicFiles.execute(baseUrl, versionFolder, actualVersion);

        String url = baseUrl +"/picms/func/device.php";
        PostDeviceInfoTask postDeviceInfoTask = new PostDeviceInfoTask(username, password, context);
        postDeviceInfoTask.execute(url, actualVersion);
    }
}