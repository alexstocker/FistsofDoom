package com.html5live.fistsofdoom;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.text.DecimalFormat;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
 
public class HomeView extends Activity {
 
	Button button;
    TextView tvIsConnected;
    TextView total;
    TextView count0;
    TextView count1;
    TextView count2;
    TextView count3;
    TextView count4;
    
    @Override
    public void onResume()
        {  // After a pause OR at startup
        super.onResume();
        setContentView(R.layout.main);
		addListenerOnButton();
		
		// get reference to the views
        tvIsConnected = (TextView) findViewById(R.id.tvIsConnected);
        total = (TextView) findViewById(R.id.total);
        count0 = (TextView) findViewById(R.id.count0);
        count1 = (TextView) findViewById(R.id.count1);
        count2 = (TextView) findViewById(R.id.count2);
        count3 = (TextView) findViewById(R.id.count3);
        count4 = (TextView) findViewById(R.id.count4);
        
        // check if you are connected or not
        if(isConnected()){
            tvIsConnected.setBackgroundColor(0x00000000);
            tvIsConnected.setText("You are connected");
        }
        else{
            tvIsConnected.setText("You are NOT conncted");
        }
 
        // call AsynTask to perform network operation on separate thread
        new HttpAsyncTask().execute("http://www.derkreuzzug.com/api/total/");
         }
 
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.main);
		addListenerOnButton();
		
		// get reference to the views
        tvIsConnected = (TextView) findViewById(R.id.tvIsConnected);
        total = (TextView) findViewById(R.id.total);
        count0 = (TextView) findViewById(R.id.count0);
        count1 = (TextView) findViewById(R.id.count1);
        count2 = (TextView) findViewById(R.id.count2);
        count3 = (TextView) findViewById(R.id.count3);
        count4 = (TextView) findViewById(R.id.count4);
        
        // check if you are connected or not
        if(isConnected()){
            tvIsConnected.setBackgroundColor(0x00000000);
            tvIsConnected.setText("You are connected");
        }
        else{
            tvIsConnected.setText("You are NOT conncted");
        }
 
        // call AsynTask to perform network operation on separate thread
        new HttpAsyncTask().execute("http://www.derkreuzzug.com/api/total/");
        
	}
 
	public void addListenerOnButton() {
 
		final Context context = this;
 
		button = (Button) findViewById(R.id.button1);
 
		button.setOnClickListener(new OnClickListener() {
 
			@Override
			public void onClick(View arg0) {
 
			    Intent intent = new Intent(context, AddRecord.class);
                            startActivity(intent);   
 
			}
 
		});
 
	}
	
	public static String GET(String url){
        InputStream inputStream = null;
        String result = "";
        try {
 
            // create HttpClient
            HttpClient httpclient = new DefaultHttpClient();
 
            // make GET request to the given URL
            HttpResponse httpResponse = httpclient.execute(new HttpGet(url));
 
            // receive response as inputStream
            inputStream = httpResponse.getEntity().getContent();
 
            // convert inputstream to string
            if(inputStream != null)
                result = convertInputStreamToString(inputStream);
            else
                result = "Did not work!";
 
        } catch (Exception e) {
            Log.d("InputStream", e.getLocalizedMessage());
        }
 
        return result;
    }
 
    private static String convertInputStreamToString(InputStream inputStream) throws IOException{
        BufferedReader bufferedReader = new BufferedReader( new InputStreamReader(inputStream));
        String line = "";
        String result = "";
        while((line = bufferedReader.readLine()) != null)
            result += line;
 
        inputStream.close();
        return result;
 
    }
    
    @Override
    public boolean onCreateOptionsMenu(Menu menu){
        super.onCreateOptionsMenu(menu);
        MenuInflater hardwaremenu = getMenuInflater();
        hardwaremenu.inflate(R.menu.menu, menu);
        return true;
    }
    
    @Override
    public boolean onOptionsItemSelected(MenuItem item){
        switch (item.getItemId()){
        case R.id.feedback:
            Intent Email = new Intent(Intent.ACTION_SEND);
            Email.setType("text/email");
            Email.putExtra(Intent.EXTRA_EMAIL, new String[] { "casualties@derkreuzzug.com" });
            Email.putExtra(Intent.EXTRA_SUBJECT, "Feedback to your App Fists of Doom");
            Email.putExtra(Intent.EXTRA_TEXT, " " + "");
            startActivity(Intent.createChooser(Email, "Send Feedback:"));
            return true;
        }
		return false;
    }
 
    public boolean isConnected(){
        ConnectivityManager connMgr = (ConnectivityManager) getSystemService(Activity.CONNECTIVITY_SERVICE);
            NetworkInfo networkInfo = connMgr.getActiveNetworkInfo();
            if (networkInfo != null && networkInfo.isConnected()) 
                return true;
            else
                return false;   
    }
    private class HttpAsyncTask extends AsyncTask<String, Void, String> {
        @Override
        protected String doInBackground(String... urls) {
 
            return GET(urls[0]);
        }
        // onPostExecute displays the results of the AsyncTask.
        @Override
        protected void onPostExecute(String result) {
            Toast.makeText(getBaseContext(), "Received!", Toast.LENGTH_LONG).show();
            try{
                JSONObject json=new JSONObject(result);
                try {
                String t=json.getString("total");
                int value = Integer.parseInt(t);
                DecimalFormat fmt = new DecimalFormat();

                fmt.setGroupingSize(3);
                fmt.setGroupingUsed(true);
                
                String c0=json.getString("count_0");
                String c1=json.getString("count_1");
                String c2=json.getString("count_2");
                String c3=json.getString("count_3");
                String c4=json.getString("count_4");
                total.setText(fmt.format(value));
                count0.setText(c0);
                count1.setText(c1);
                count2.setText(c2);
                count3.setText(c3);
                count4.setText(c4);
                } catch (JSONException e) {
                e.printStackTrace();
                }

            }
            catch (JSONException e) {
                  e.printStackTrace();
                } 
            catch (Exception e) {
                    // TODO Auto-generated catch block
                    e.printStackTrace();
                }
       }
    }
 
}