<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->string('venue');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('registration_deadline');
            $table->integer('capacity');
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->json('tags')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('requires_approval')->default(false);
            $table->text('cancellation_policy')->nullable();
            $table->text('refund_policy')->nullable();
            $table->json('additional_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'start_date']);
            $table->index(['city', 'state']);
            $table->index(['price', 'currency']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
