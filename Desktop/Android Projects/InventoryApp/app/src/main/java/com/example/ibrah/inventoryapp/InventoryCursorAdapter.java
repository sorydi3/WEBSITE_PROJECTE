package com.example.ibrah.inventoryapp;

import android.content.ContentResolver;
import android.content.ContentUris;
import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.support.v4.widget.CursorAdapter;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.example.ibrah.inventoryapp.dataBase.InventoryContract;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;

/**
 * Created by ibrah on 26/07/2017.
 */

public class InventoryCursorAdapter extends CursorAdapter {
    private static final String LOG_TAG = "InventoryCursorAdapter";

    public InventoryCursorAdapter(Context context, Cursor c) {
        super(context, c, 0 /* flags */);
    }

    @Override
    public View newView(Context context, Cursor cursor, ViewGroup parent) {
        return LayoutInflater.from(context).inflate(R.layout.list_item_view, parent, false);
    }

    @Override
    public void bindView(View view, final Context context, final Cursor cursor) {
        // Find fields to populate in inflated template
        TextView product = (TextView) view.findViewById(R.id.list_item_product);
        TextView price = (TextView) view.findViewById(R.id.list_item_price);
        TextView quantity = (TextView) view.findViewById(R.id.list_item_quantity);
        Button button = (Button) view.findViewById(R.id.list_item_sell);


        final String name = cursor.getString(cursor.getColumnIndexOrThrow(InventoryContract.ProductEntry.COLUMN_NAME_PRODUCT));
        int pricee = cursor.getInt(cursor.getColumnIndexOrThrow(InventoryContract.ProductEntry.COLUMN_NAME_PRICE));

        final int mQuantity = cursor.getInt(cursor.getColumnIndexOrThrow(InventoryContract.ProductEntry.COLUMN_NAME_QUANTITY));
        final long id = cursor.getLong(cursor.getColumnIndex(InventoryContract.ProductEntry._ID));
        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mQuantity > 0) {
                    // Create a ContentValues object to update the product quantity into the database.
                    ContentValues values = new ContentValues();
                    values.put(InventoryContract.ProductEntry.COLUMN_NAME_QUANTITY, mQuantity - 1);

                    // Create URI pointing to the current product and update it with given ContentValues.
                    Log.i(LOG_TAG, "ID PRODUCT IS EQUAL TO----" + id);
                    Log.i(LOG_TAG, "ID PRODUCT IS EQUAL TO----" + name);
                    Uri uri = ContentUris.withAppendedId(InventoryContract.ProductEntry.CONTENT_URI, id);
                    Log.i(LOG_TAG, "ID PRODUCT IS EQUAL TO----" + uri);
                    context.getContentResolver().update(uri, values, null, null);
                }
            }
        });
        price.setText(String.valueOf(pricee));
        product.setText(name);
        quantity.setText(String.valueOf(mQuantity));

    }


}
