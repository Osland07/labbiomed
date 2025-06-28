<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ClearQrCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear QR code cache and test QR generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing QR code cache...');
        
        // Clear various caches
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('route:clear');
        
        $this->info('Testing QR code generation...');
        
        try {
            // Test QR code generation
            $qrCode = QrCode::size(200)->format('png')->generate('https://example.com');
            
            if ($qrCode) {
                $this->info('✅ QR code generation successful!');
                $this->info('QR code size: ' . strlen($qrCode) . ' bytes');
            } else {
                $this->error('❌ QR code generation failed - no output');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ QR code generation failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
        
        $this->info('QR cache clear and test completed!');
    }
} 