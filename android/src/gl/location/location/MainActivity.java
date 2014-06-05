package gl.location.location;

import android.app.*;
import android.os.*;
import android.view.*;
import android.widget.*;
import android.content.Context;

import android.app.Activity;
import android.os.Bundle;

import android.net.Uri;

import android.content.Intent;

import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.webkit.WebSettings;

public class MainActivity extends Activity
{
    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        Intent sendIntent = new Intent (Intent.ACTION_VIEW);
        Uri uri = Uri.parse("http:/location.gl/MIT");
		sendIntent.setData(uri);
		startActivity(sendIntent);
    }
}
