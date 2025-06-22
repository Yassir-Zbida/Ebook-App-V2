<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Illuminate\Console\Command;

class CreateCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:create 
                            {code : The coupon code}
                            {--description= : Description of the coupon}
                            {--type=percentage : Type of discount (percentage or fixed)}
                            {--value=10 : Discount value (percentage or fixed amount)}
                            {--minimum-amount=0 : Minimum order amount required}
                            {--usage-limit= : Maximum number of times this coupon can be used}
                            {--usage-limit-per-user=1 : Maximum number of times per user}
                            {--valid-from= : Valid from date (YYYY-MM-DD)}
                            {--valid-until= : Valid until date (YYYY-MM-DD)}
                            {--active=true : Whether the coupon is active}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new coupon in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $code = $this->argument('code');
        
        // Check if coupon already exists
        if (Coupon::where('code', $code)->exists()) {
            $this->error("Coupon with code '{$code}' already exists!");
            return 1;
        }

        $data = [
            'code' => strtoupper($code),
            'description' => $this->option('description') ?: "Discount coupon: {$code}",
            'type' => $this->option('type'),
            'value' => $this->option('value'),
            'minimum_amount' => $this->option('minimum-amount'),
            'usage_limit' => $this->option('usage-limit'),
            'usage_limit_per_user' => $this->option('usage-limit-per-user'),
            'used_count' => 0,
            'is_active' => $this->option('active') === 'true',
            'valid_from' => $this->option('valid-from') ? now()->parse($this->option('valid-from')) : null,
            'valid_until' => $this->option('valid-until') ? now()->parse($this->option('valid-until')) : null,
            'applicable_ebooks' => null,
            'applicable_categories' => null,
            'metadata' => [
                'created_by' => 'console',
                'created_at' => now()->toISOString(),
            ],
        ];

        try {
            $coupon = Coupon::create($data);
            
            $this->info("✅ Coupon created successfully!");
            $this->table(
                ['Field', 'Value'],
                [
                    ['Code', $coupon->code],
                    ['Description', $coupon->description],
                    ['Type', $coupon->type],
                    ['Value', $coupon->value],
                    ['Minimum Amount', $coupon->minimum_amount ?: 'None'],
                    ['Usage Limit', $coupon->usage_limit ?: 'Unlimited'],
                    ['Usage Limit Per User', $coupon->usage_limit_per_user],
                    ['Active', $coupon->is_active ? 'Yes' : 'No'],
                    ['Valid From', $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : 'Immediate'],
                    ['Valid Until', $coupon->valid_until ? $coupon->valid_until->format('Y-m-d') : 'No Expiry'],
                ]
            );
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Failed to create coupon: " . $e->getMessage());
            return 1;
        }
    }
}
