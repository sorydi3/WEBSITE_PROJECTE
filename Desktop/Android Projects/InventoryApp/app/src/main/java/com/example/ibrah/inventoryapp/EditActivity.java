package com.example.ibrah.inventoryapp;

import android.app.Activity;
import android.content.ContentValues;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.Build;
import android.support.design.widget.FloatingActionButton;
import android.support.v4.app.NavUtils;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.example.ibrah.inventoryapp.dataBase.InventoryContract;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;

public class EditActivity extends AppCompatActivity {

    private static final String LOG_TAG = MainActivity.class.getSimpleName();

    private static final int PICK_IMAGE_REQUEST = 0;
    private static final int SEND_MAIL_REQUEST = 1;

    private static final String STATE_URI = "STATE_URI";

    private ImageView mImageView;
    private TextView mTextView;
    private Button mFab;

    private Uri mUri;

    private EditText mNameEditText;


    private EditText mStockEditText;


    private EditText mPriceEditText;

    private EditText mEmeilEditText;

    private Button save;

    private EditText mNumberEditText;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit);
        invalidateOptionsMenu();
        mTextView = (TextView) findViewById(R.id.uriii);
        mFab = (Button) findViewById(R.id.buttonedit);
        save = (Button) findViewById(R.id.save);
        save.setVisibility(View.GONE);
        Toast.makeText(this, "You must select an image to save product", Toast.LENGTH_LONG).show();
        mNameEditText = (EditText) findViewById(R.id.edit_name);
        mPriceEditText = (EditText) findViewById(R.id.edit_price);
        mStockEditText = (EditText) findViewById(R.id.edit_stock);
        mEmeilEditText = (EditText) findViewById(R.id.edit_emeil);
        mNumberEditText = (EditText) findViewById(R.id.edit_number);
        save.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                insetproduct();
                finish();
            }
        });
        mFab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openImageSelector();
            }
        });
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu options from the res/menu/menu_catalog.xml file.
        // This adds menu items to the app bar.
        getMenuInflater().inflate(R.menu.menu_edit, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // User clicked on a menu option in the app bar overflow menu
        switch (item.getItemId()) {
            // Respond to a click on the "Insert dummy data" menu option
            case R.id.action_delete:
                return true;
            // Respond to a click on the "Up" arrow button in the app bar
            case android.R.id.home:
                // Navigate back to parent activity (CatalogActivity)
                NavUtils.navigateUpFromSameTask(this);
                return true;
        }
        return super.onOptionsItemSelected(item);
    }

    public void openImageSelector() {
        Intent intent;

        if (Build.VERSION.SDK_INT < 19) {
            intent = new Intent(Intent.ACTION_GET_CONTENT);
        } else {
            intent = new Intent(Intent.ACTION_OPEN_DOCUMENT);
            intent.addCategory(Intent.CATEGORY_OPENABLE);
        }

        intent.setType("image/*");
        startActivityForResult(Intent.createChooser(intent, "Select Picture"), PICK_IMAGE_REQUEST);
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent resultData) {
        // The ACTION_OPEN_DOCUMENT intent was sent with the request code READ_REQUEST_CODE.
        // If the request code seen here doesn't match, it's the response to some other intent,
        // and the below code shouldn't run at all.

        if (requestCode == PICK_IMAGE_REQUEST && resultCode == Activity.RESULT_OK) {
            // The document selected by the user won't be returned in the intent.
            // Instead, a URI to that document will be contained in the return intent
            // provided to this method as a parameter.  Pull that uri using "resultData.getData()"

            if (resultData != null) {
                mUri = resultData.getData();
                save.setVisibility(View.VISIBLE);
                Log.i(LOG_TAG, "Uri: " + mUri.toString());

                mTextView.setText(mUri.toString());
                // mImageView.setImageBitmap(getBitmapFromUri(mUri));
            }
        } else if (requestCode == SEND_MAIL_REQUEST && resultCode == Activity.RESULT_OK) {

        }
    }

    public void insetproduct() {
        // Read from input fields
        // Use trim to eliminate leading or trailing white space
        String mmNameEditText = mNameEditText.getText().toString().trim();
        String mmPriceEditText = mPriceEditText.getText().toString().trim();
        String mmStockEditText = mStockEditText.getText().toString().trim();
        String mmEmeilEditText = mEmeilEditText.getText().toString();
        String mmNumberEditText = mNumberEditText.getText().toString().trim();

        if (
                TextUtils.isEmpty(mmNameEditText) && TextUtils.isEmpty(mmEmeilEditText)) {
            // Since no fields were modified, we can return early without creating a new pet.
            // No need to create ContentValues and no need to do any ContentProvider operations.
            Toast.makeText(this, "Name Product && Emeil are mandatory to save new product", Toast.LENGTH_SHORT).show();
            return;
        }

        int quantity = 0;
        if (!TextUtils.isEmpty(mmStockEditText)) {
            quantity = Integer.parseInt(mmStockEditText);
        }
        // If the price is not provided by the user, don't try to parse the string into an
        // integer value. Use 0 by default.
        int price = 0;
        if (!TextUtils.isEmpty(mmPriceEditText)) {
            price = Integer.parseInt(mmPriceEditText);
        }

        int number = 0;
        if (!TextUtils.isEmpty(mmNumberEditText)) {
            number = Integer.parseInt(mmNumberEditText);
        }

        // Create a ContentValues object where column names are the keys,
        // and pet attributes from the editor are the values.
        ContentValues values = new ContentValues();
        values.put(InventoryContract.ProductEntry.COLUMN_NAME_PRODUCT, mmNameEditText);
        values.put(InventoryContract.ProductEntry.COLUMN_NAME_PRICE, price);
        values.put(InventoryContract.ProductEntry.COLUMN_NAME_QUANTITY, quantity);
        values.put(InventoryContract.ProductEntry.COLUMN_NAME_SUPPLIER_NUMBER, number);
        values.put(InventoryContract.ProductEntry.COLUMN_NAME_SUPPLIER_EMAIL, mmEmeilEditText);

        values.put(InventoryContract.ProductEntry.COLUMN_NAME_IMAGE, mUri.toString());

        // If the weight is not provided by the user, don't try to parse the string into an
        // integer value. Use 0 by default.
        Uri newUri = getContentResolver().insert(InventoryContract.ProductEntry.CONTENT_URI, values);
        if (newUri == null) {
            Toast.makeText(this, "error while inserting", Toast.LENGTH_SHORT).show();
        } else {
            Toast.makeText(this, "succeful insersion", Toast.LENGTH_SHORT).show();
        }


    }

    public Bitmap getBitmapFromUri(Uri uri) {

        if (uri == null || uri.toString().isEmpty())
            return null;

        // Get the dimensions of the View
        int targetW = mImageView.getWidth();
        int targetH = mImageView.getHeight();

        InputStream input = null;
        try {
            input = this.getContentResolver().openInputStream(uri);

            // Get the dimensions of the bitmap
            BitmapFactory.Options bmOptions = new BitmapFactory.Options();
            bmOptions.inJustDecodeBounds = true;
            BitmapFactory.decodeStream(input, null, bmOptions);
            input.close();

            int photoW = bmOptions.outWidth;
            int photoH = bmOptions.outHeight;

            // Determine how much to scale down the image
            int scaleFactor = Math.min(photoW / targetW, photoH / targetH);

            // Decode the image file into a Bitmap sized to fill the View
            bmOptions.inJustDecodeBounds = false;
            bmOptions.inSampleSize = scaleFactor;
            bmOptions.inPurgeable = true;

            input = this.getContentResolver().openInputStream(uri);
            Bitmap bitmap = BitmapFactory.decodeStream(input, null, bmOptions);
            input.close();
            return bitmap;

        } catch (FileNotFoundException fne) {
            Log.e(LOG_TAG, "Failed to load image.", fne);
            return null;
        } catch (Exception e) {
            Log.e(LOG_TAG, "Failed to load image.", e);
            return null;
        } finally {
            try {
                input.close();
            } catch (IOException ioe) {

            }
        }
    }

}
