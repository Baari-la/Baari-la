<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'purchase_order_documents',
            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | PURCHASE ORDER
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'purchase_order_id'
                )->constrained(
                    'purchase_orders'
                )->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | UPLOADED BY USER
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'uploaded_by'
                )->constrained(
                    'users'
                )->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | DOCUMENT
                |--------------------------------------------------------------------------
                */

                $table->enum(
                    'document_type',
                    [
                        'invoice',
                        'packing_list',
                        'bill_of_lading',
                        'air_waybill',
                        'certificate_of_origin',
                        'insurance_certificate',
                        'inspection_certificate',
                        'other',
                    ]
                );

                $table->string(
                    'document_number'
                )->nullable();

                $table->string(
                    'file_path'
                );

                $table->text(
                    'remarks'
                )->nullable();

                $table->timestamps();

                /*
                |--------------------------------------------------------------------------
                | INDEXES
                |--------------------------------------------------------------------------
                */

                $table->index(
                    'purchase_order_id'
                );

                $table->index(
                    'uploaded_by'
                );

                $table->index(
                    'document_type'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'purchase_order_documents'
        );
    }
};