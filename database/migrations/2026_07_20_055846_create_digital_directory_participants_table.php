<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'digital_directory_participants',
            function (
                Blueprint $table
            ) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | Package
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'package'
                );

                /*
                |--------------------------------------------------------------------------
                | Company Information
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'company_name'
                );

                $table->string(
                    'pic_name'
                );

                $table->string(
                    'position'
                )->nullable();

                $table->string(
                    'email'
                );

                $table->string(
                    'phone'
                )->nullable();

                $table->string(
                    'website'
                )->nullable();

                $table->string(
                    'company_type'
                )->nullable();

                $table->string(
                    'country'
                )->default(
                    'Indonesia'
                );

                $table->string(
                    'city'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Payment
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'payment_method'
                )->nullable();

                $table->string(
                    'payment_gateway'
                )->nullable();

                $table->string(
                    'transaction_id'
                )->nullable();

                $table->string(
                    'external_transaction_id'
                )->nullable();

                $table->decimal(
                    'amount',
                    15,
                    2
                )->default(
                    0
                );

                $table->string(
                    'currency'
                )->default(
                    'IDR'
                );

                $table->string(
                    'payment_reference'
                )->nullable();

                $table->string(
                    'payment_receipt'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | QRIS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'qris_reference'
                )->nullable();

                $table->text(
                    'qris_payload'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Virtual Account
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'virtual_account_number'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Gateway Response
                |--------------------------------------------------------------------------
                */

                $table->json(
                    'gateway_response'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Payment Status
                |--------------------------------------------------------------------------
                */

                $table->enum(
                    'payment_status',
                    [
                        'draft',
                        'waiting_payment',
                        'pending_verification',
                        'paid',
                        'verified',
                        'expired',
                        'rejected',
                    ]
                )->default(
                    'draft'
                );

                $table->timestamp(
                    'paid_at'
                )->nullable();

                $table->timestamp(
                    'payment_verified_at'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Activation
                |--------------------------------------------------------------------------
                */

                $table->enum(
                    'activation_status',
                    [
                        'draft',
                        'submitted',
                        'active',
                        'inactive',
                    ]
                )->default(
                    'draft'
                );

                /*
                |--------------------------------------------------------------------------
                | Visibility Services
                |--------------------------------------------------------------------------
                */

                $table->boolean(
                    'visibility_score_active'
                )->default(
                    false
                );

                $table->boolean(
                    'company_passport_active'
                )->default(
                    false
                );

                $table->boolean(
                    'executive_dashboard_active'
                )->default(
                    false
                );

                $table->boolean(
                    'smart_matching_active'
                )->default(
                    false
                );

                $table->boolean(
                    'build_supply_chain_active'
                )->default(
                    false
                );

                /*
                |--------------------------------------------------------------------------
                | Verification
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger(
                    'verified_by'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | Admin Notes
                |--------------------------------------------------------------------------
                */

                $table->text(
                    'admin_notes'
                )->nullable();

               /*
|--------------------------------------------------------------------------
| Future Integration
|--------------------------------------------------------------------------
*/

                $table->foreignId(
                    'user_id'
                )
                ->nullable()
                ->constrained()
                ->nullOnDelete();

                $table->integer(
                    'company_id'
                )
                ->nullable()
                ->index();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'digital_directory_participants'
        );
    }
};