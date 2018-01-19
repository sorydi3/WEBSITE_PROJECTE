package com.example.ibrah.inventoryapp.dataBase;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

/**
 * Created by ibrah on 24/07/2017.
 */

public class InventoryDbHelper extends SQLiteOpenHelper {
    public static final String DATABASE_NAME = "inventory.db";  // Database filename.
    public static final int DATABASE_VERSION = 1;               // Current version of the database.

    /**
     * Constructor for this class.
     *
     * @param context is the context to open or create the database.
     */
    public InventoryDbHelper(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    /**
     * Called when the database is created for the first time. This is where the creation of tables
     * and the initial population of the tables should happen.
     *
     * @param db is the database.
     */
    @Override
    public void onCreate(SQLiteDatabase db) {
        // SQL statement for creating the "products" table.
        String SQL_CREATE_ENTRIES = "CREATE TABLE " + InventoryContract.ProductEntry.TABLE_NAME + " (" +
                InventoryContract.ProductEntry._ID + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
                InventoryContract.ProductEntry.COLUMN_NAME_PRODUCT + " TEXT NOT NULL,  " +
                InventoryContract.ProductEntry.COLUMN_NAME_IMAGE + " TEXT NOT NULL,  " +
                InventoryContract.ProductEntry.COLUMN_NAME_PRICE + " INTEGER NOT NULL DEFAULT 0, " +
                InventoryContract.ProductEntry.COLUMN_NAME_QUANTITY + " INTEGER NOT NULL DEFAULT 1, " +
                InventoryContract.ProductEntry.COLUMN_NAME_SUPPLIER_NUMBER + " INTEGER NOT NULL DEFAULT 0, " +
                InventoryContract.ProductEntry.COLUMN_NAME_SUPPLIER_EMAIL + " TEXT NOT NULL);";
        // Execute the SQL statement.
        db.execSQL(SQL_CREATE_ENTRIES);
    }

    /**
     * Called when the database needs to be upgraded. The implementation should use this method to
     * drop tables, add tables, or do anything else it needs to upgrade to the new schema version.
     *
     * @param db         is the database.
     * @param oldVersion is the old database version.
     * @param newVersion is the new database version.
     */
    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        // The database is still at version 1, so there's nothing to do be done here.
    }
}
