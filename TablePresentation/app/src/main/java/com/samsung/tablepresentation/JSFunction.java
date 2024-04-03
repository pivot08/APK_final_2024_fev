package com.samsung.tablepresentation;

import android.webkit.JavascriptInterface;

import com.samsung.tablepresentation.MainActivity;
import com.samsung.tablepresentation.PostNavigationControlTask;

public class JSFunction {

    private MainActivity mainActivity;

    public JSFunction(MainActivity activity) {
        mainActivity = activity;
    }

    @JavascriptInterface
    public void registerNavigation(String actionID, String templateID, String templateContentID, String actionDate) {
        if (mainActivity.isConnectedToInternet()) {
            String url = mainActivity.baseUrl +"/picms/func/navigation.php";
            PostNavigationControlTask postNavigationControlTask = new PostNavigationControlTask(mainActivity.username, mainActivity.password, mainActivity.context);
            postNavigationControlTask.execute(url, mainActivity.actualVersion, actionID, templateID, templateContentID, actionDate);
        }
    }
}
