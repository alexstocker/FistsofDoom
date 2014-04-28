package com.html5live.fistsofdoom;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;

import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;


// import everything you need
import android.app.Activity;
import android.app.DatePickerDialog;
import android.app.Dialog;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.Toast;

public class AddRecord extends Activity {

	Button btnSelectDate,sendButton;
	
	static final int DATE_DIALOG_ID = 0;
 
    EditText msgTextField;
    EditText locTextField;
    EditText casualties_christians;
    EditText casualties_muslims;
    EditText casualties_jewish;
    EditText casualties_hindus;
    EditText casualties_unknown;
    EditText more_link;
    
    // variables to save user selected date and time
public  int year,month,day;  
// declare  the variables to Show/Set the date and time when Time and  Date Picker Dialog first appears
private int mYear, mMonth, mDay; 

	public AddRecord()
	{
	            // Assign current Date and Time Values to Variables
	            final Calendar c = Calendar.getInstance();
	            mYear = c.get(Calendar.YEAR);
	            mMonth = c.get(Calendar.MONTH);
	            mDay = c.get(Calendar.DAY_OF_MONTH);
	}

    /** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        // load the layout
        setContentView(R.layout.addrecord); 
        
     // get the references of buttons
        btnSelectDate=(Button)findViewById(R.id.buttonSelectDate);
        
     // Set ClickListener on btnSelectDate 
        btnSelectDate.setOnClickListener(new View.OnClickListener() {
            
            public void onClick(View v) {
                // Show the DatePickerDialog
                 showDialog(DATE_DIALOG_ID);
            }
        });

        // make message text field object
        msgTextField = (EditText) findViewById(R.id.msgTextField);
        locTextField = (EditText) findViewById(R.id.locTextField);
        casualties_christians = (EditText) findViewById(R.id.casualties_christians);
        casualties_muslims = (EditText) findViewById(R.id.casualties_muslims);
        casualties_jewish = (EditText) findViewById(R.id.casualties_jewish);
        casualties_hindus = (EditText) findViewById(R.id.casualties_hindus);
        casualties_unknown = (EditText) findViewById(R.id.casualties_unknown);
        more_link = (EditText) findViewById(R.id.more_link);
        
        // make send button object
        sendButton = (Button) findViewById(R.id.sendButton);

    }

    // this is the function that gets called when you click the button
    public void send(View v)
    {
        // get the message from the message text box

        
        PostTask posttask;
        // make sure the fields are not empty

        posttask = new PostTask();
        posttask.execute();


    }
    
 // Register  DatePickerDialog listener
    private DatePickerDialog.OnDateSetListener mDateSetListener =
                           new DatePickerDialog.OnDateSetListener() {
                       // the callback received when the user "sets" the Date in the DatePickerDialog
                               public void onDateSet(DatePicker view, int yearSelected,
                                                     int monthOfYear, int dayOfMonth) {
                                  year = yearSelected;
                                  month = monthOfYear+1;
                                  day = dayOfMonth;
                                  // Set the Selected Date in Select date Button
                                  btnSelectDate.setText(year+"-"+month+"-"+day);
                               }
                           };

   // Method automatically gets Called when you call showDialog()  method
                           @Override
                           protected Dialog onCreateDialog(int id) {
                               switch (id) {
                               case DATE_DIALOG_ID:
                        // create a new DatePickerDialog with values you want to show 
                                   return new DatePickerDialog(this,
                                               mDateSetListener,
                                               mYear, mMonth, mDay);
                              
                               }
                               return null;
                           }
                           
       public class PostTask extends AsyncTask<Void, String, Boolean> {

		@Override
           protected Boolean doInBackground(Void...params) {
               boolean result = false;
               
               String msg = msgTextField.getText().toString();  
               String loc = locTextField.getText().toString();
               String date = btnSelectDate.getText().toString();
               String casc = casualties_christians.getText().toString();
               String casm = casualties_muslims.getText().toString();
               String casj = casualties_jewish.getText().toString();
               String cash = casualties_hindus.getText().toString();
               String casu = casualties_unknown.getText().toString();
               String link = more_link.getText().toString();
               
               try{
		           HttpClient httpclient = new DefaultHttpClient();
		   	   	   HttpPost httppost = new HttpPost("http://www.derkreuzzug.com/app.php?func=insert");
		   	   	   List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
		   	   	   nameValuePairs.add(new BasicNameValuePair("id", "12345"));
		  	       nameValuePairs.add(new BasicNameValuePair("message", msg));
		  	       nameValuePairs.add(new BasicNameValuePair("location", loc));
		  	       nameValuePairs.add(new BasicNameValuePair("date", date));
		  	       nameValuePairs.add(new BasicNameValuePair("casc", casc));
		  	       nameValuePairs.add(new BasicNameValuePair("casm", casm));
		  	       nameValuePairs.add(new BasicNameValuePair("casj", casj));
		  	       nameValuePairs.add(new BasicNameValuePair("cash", cash));
		  	       nameValuePairs.add(new BasicNameValuePair("casu", casu));
		  	       nameValuePairs.add(new BasicNameValuePair("link", link));
		  	       httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
		  	       HttpResponse responsePost = httpclient.execute(httppost);
		  	       HttpEntity resEntity = responsePost.getEntity();
		  	       String str2 = EntityUtils.toString(resEntity);
               }catch(Exception e){
            	   Log.e("log_tag", "Error in http connection "+e.toString());
               }
         	 

               //If you want to do something on the UI use progress update

               publishProgress("progress");
               return result;
           }

           protected void onProgressUpdate(String... progress) {
               StringBuilder str = new StringBuilder();
                   for (int i = 1; i < progress.length; i++) {
                       str.append(progress[i] + " ");
                   }

           }
           
           @Override
		protected void onPostExecute(Boolean result) {
			// TODO Auto-generated method stub
			super.onPostExecute(result);
	           AddRecord.this.runOnUiThread(new Runnable() {
	                public void run() {
			  	    	   btnSelectDate.setText("Datum");
			  	    	   msgTextField.setText(""); // clear text box
			  	    	   locTextField.setText(""); // clear text box
			  	    	   casualties_christians.setText(""); // clear text box
			  	    	   casualties_muslims.setText(""); // clear text box
			  	    	   casualties_jewish.setText(""); // clear text box
			  	    	   casualties_hindus.setText(""); // clear text box
			  	    	   casualties_unknown.setText(""); // clear text box
			  	    	   more_link.setText(""); // clear text box
			  	    	   Toast.makeText(getBaseContext(),"Added successfully",Toast.LENGTH_SHORT).show();
	                }
	              });
		}
       }
    
}
