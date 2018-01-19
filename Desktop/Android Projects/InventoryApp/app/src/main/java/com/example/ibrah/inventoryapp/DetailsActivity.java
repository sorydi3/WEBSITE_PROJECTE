package com.example.ibrah.inventoryapp;


import android.app.AlertDialog;
import android.app.LoaderManager;
import android.content.ContentUris;
import android.content.ContentValues;
import android.content.CursorLoader;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.Loader;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;

import android.support.design.widget.FloatingActionButton;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;


import com.example.ibrah.inventoryapp.dataBase.InventoryContract;

import static android.R.attr.button;

public class DetailsActivity extends AppCompatActivity implements LoaderManager.LoaderCallbacks<Cursor> {
    private Uri mCurrenUri;
    private TextView product;
    private TextView price;
    private TextView quantity;
    private TextView suplier_number;
    private TextView suplier_emeil;
    private ImageView imageView;
    private int mQuantity;
    private String emeilsuplier;

    private void showDeleteConfirmationDialog() {
        // Create an AlertDialog.Builder and set the message, and click listeners
        // for the postivie and negative buttons on the dialog.
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setMessage("Your are about to delete the current product");
        builder.setPositiveButton("Delete", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // User clicked the "Delete" button, so delete the pet.
                delete();
                finish();
            }
        });
        builder.setNegativeButton("Cansel", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
                // User clicked the "Cancel" button, so dismiss the dialog
                // and continue editing the pet.
                if (dialog != null) {
                    dialog.dismiss();
                }
            }
        });
        // Create and show the AlertDialog
        AlertDialog alertDialog = builder.create();
        alertDialog.show();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_details);
        Intent intent = getIntent();
        mCurrenUri = intent.getData();
        product = (TextView) findViewById(R.id.name_productD);
        price = (TextView) findViewById(R.id.price_productD);
        quantity = (TextView) findViewById(R.id.stock_productD);
        suplier_number = (TextView) findViewById(R.id.suplier_numberD);
        suplier_emeil = (TextView) findViewById(R.id.suplier_emeilD);
        imageView = (ImageView) findViewById(R.id.product_imageD);
        getLoaderManager().initLoader(1, null, this);
        Button add = (Button) findViewById(R.id.add);
        Button minus = (Button) findViewById(R.id.minus);
        add.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                // Create a ContentValues object to update the product quantity into the database.
                ContentValues values = new ContentValues();
                values.put(InventoryContract.ProductEntry.COLUMN_NAME_QUANTITY, mQuantity + 1);

                // Create URI pointing to the current product and update it with given ContentValues.
                getContentResolver().update(mCurrenUri, values, null, null);

            }
        });
        minus.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mQuantity > 0) {
                    // Create a ContentValues object to update the product quantity into the database.
                    ContentValues values = new ContentValues();
                    values.put(InventoryContract.ProductEntry.COLUMN_NAME_QUANTITY, mQuantity - 1);

                    // Create URI pointing to the current product and update it with given ContentValues.
                    getContentResolver().update(mCurrenUri, values, null, null);
                }
            }
        });
        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                Intent i = new Intent(Intent.ACTION_SEND);
                i.setType("message/rfc822");
                i.putExtra(Intent.EXTRA_EMAIL, new String[]{emeilsuplier});
                i.putExtra(Intent.EXTRA_SUBJECT, "Request Products");
                i.putExtra(Intent.EXTRA_TEXT, "Dear Supplier I ..." + mQuantity);
                try {
                    startActivity(Intent.createChooser(i, "Send mail..."));
                } catch (android.content.ActivityNotFoundException ex) {
                    Toast.makeText(DetailsActivity.this, "There are no email clients installed.", Toast.LENGTH_SHORT).show();
                }

            }
        });
    }

    @Override
    public Loader<Cursor> onCreateLoader(int id, Bundle args) {
        String[] projection = {InventoryContract.ProductEntry._ID,
                InventoryContract.ProductEntry.COLUMN_NAME_PRODUCT,
                InventoryContract.ProductEntry.COLUMN_NAME_PRICE,
                InventoryContract.ProductEntry.COLUMN_NAME_QUANTITY,
                InventoryContract.ProductEntry.COLUMN_NAME_IMAGE,
                InventoryContract.ProductEntry.COLUMN_NAME_SUPPLIER_EMAIL,
                InventoryContract.ProductEntry.COLUMN_NAME_SUPPLIER_NUMBER,
        };
        return new CursorLoader(this, mCurrenUri, projection, null, null, null);
    }

    @Override
    public void onLoadFinished(Loader<Cursor> loader, Cursor cursor) {
        // Bail early if the cursor is null or there is less than 1 row in the cursor
        if (cursor == null || cursor.getCount() < 1) {
            return;
        }

        // Proceed with moving to the first row of the cursor and reading data from it
        // (This should be the only row in the cursor)
        if (cursor.moveToFirst()) {
            // Find the columns of pet attributes that we're interested in
            String name = cursor.getString(cursor.getColumnIndexOrThrow(InventoryContract.ProductEntry.COLUMN_NAME_PRODUCT));
            int pricee = cursor.getInt(cursor.getColumnIndexOrThrow(InventoryContract.ProductEntry.COLUMN_NAME_PRICE));
            mQuantity = cursor.getInt(cursor.getColumnIndexOrThrow(InventoryContract.ProductEntry.COLUMN_NAME_QUANTITY));
            emeilsuplier = cursor.getString(cursor.getColumnIndexOrThrow(InventoryContract.ProductEntry.COLUMN_NAME_SUPPLIER_EMAIL));
            int number = cursor.getInt(cursor.getColumnIndexOrThrow(InventoryContract.ProductEntry.COLUMN_NAME_SUPPLIER_NUMBER));
            String imageUri = cursor.getString(cursor.getColumnIndexOrThrow(InventoryContract.ProductEntry.COLUMN_NAME_IMAGE));
            imageView.setImageURI(Uri.parse(imageUri));
            price.setText(String.valueOf(pricee));
            product.setText(name);
            quantity.setText(String.valueOf(mQuantity));
            suplier_emeil.setText(emeilsuplier);
            suplier_number.setText(String.valueOf(number));
//        mImageView.setImageBitmap(getBitmapFromUri(uriImage,context,mImageView));

        }

    }

    @Override
    public void onLoaderReset(Loader<Cursor> loader) {
        imageView.setImageURI(null);
        price.setText("");
        product.setText("");
        quantity.setText("");
        suplier_emeil.setText("");
        suplier_number.setText(" ");
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
                showDeleteConfirmationDialog();
                return true;
            // Respond to a click on the "Delete all entries" menu option
            case R.id.action_delete_all_entries:
                return true;
        }
        return super.onOptionsItemSelected(item);
    }

    public void delete() {
        int rowsDeleted = getContentResolver().delete(mCurrenUri, null, null);
        if (rowsDeleted != -1) {
            Toast.makeText(this, "succefull delection", Toast.LENGTH_SHORT).show();
        } else {
            Toast.makeText(this, "error ocurred while deleting content", Toast.LENGTH_SHORT).show();
        }
    }
}
