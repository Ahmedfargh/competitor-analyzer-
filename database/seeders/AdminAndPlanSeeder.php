<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\Admin;

class AdminAndPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@compitator.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $plans = [
            [
                'name' => [
                    'en' => 'Starter',
                    'ar' => 'خطة البداية',
                ],
                'slug' => 'starter',
                'description' => [
                    'en' => 'Ideal for startups & local brands exploring market competition.',
                    'ar' => 'مثالية للشركات الناشئة والعلامات التجارية المحلية لاستكشاف المنافسة في السوق.',
                ],
                'price_egp' => 1490.00,
                'price_usd' => 29.00,
                'billing_period' => 'monthly',
                'features' => [
                    'en' => [
                        'Up to 10 Competitor Profiles',
                        'Daily Automated Web Scrapes',
                        'Gemini 2.5 Flash Market Summaries',
                        'Arabic & English Localization',
                        'Email Alerts',
                    ],
                    'ar' => [
                        'حتى 10 ملفات تعريف للمنافسين',
                        'فحص وتتبع آلي يومي للمواقع',
                        'ملخصات سوقية عبر Gemini 2.5 Flash',
                        'دعم كامل للغتين العربية والإنجليزية',
                        'تنبيهات البريد الإلكتروني الفورية',
                    ],
                ],
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => [
                    'en' => 'Professional',
                    'ar' => 'خطة المحترفين',
                ],
                'slug' => 'professional',
                'description' => [
                    'en' => 'For fast-growing companies requiring continuous intelligence & deep matrix audits.',
                    'ar' => 'للشركات سريعة النمو التي تتطلب استخبارات سوقية مستمرة وتدقيقاً استراتيجياً عميقاً.',
                ],
                'price_egp' => 3490.00,
                'price_usd' => 69.00,
                'billing_period' => 'monthly',
                'features' => [
                    'en' => [
                        'Up to 50 Competitor Profiles',
                        'Hourly Automated Scraping & Detection',
                        'Deep Gemini Competitive Gap Analysis',
                        'Full PDF / Excel Intelligence Dossiers',
                        'Multi-channel WhatsApp / Email Alerts',
                        'API & Webhook Access',
                    ],
                    'ar' => [
                        'حتى 50 ملف تعريف للمنافسين',
                        'فحص وكشف وتتبع آلي بالساعة',
                        'تحليل فجوات تنافسية عميق عبر Gemini',
                        'تقارير استخباراتية شاملة PDF / Excel',
                        'تنبيهات متعددة القنوات (واتساب والبريد)',
                        'وصول كامل لواجهة البرمجة والـ Webhooks',
                    ],
                ],
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => [
                    'en' => 'Enterprise',
                    'ar' => 'خطة المؤسسات',
                ],
                'slug' => 'enterprise',
                'description' => [
                    'en' => 'Dedicated tenant infrastructure with custom AI reasoning agents & SLA.',
                    'ar' => 'بنية تحتية مخصصة لكل مستأجر مع عملاء ذكاء اصطناعي مخصصين واتفاقية مستوى خدمة.',
                ],
                'price_egp' => 9990.00,
                'price_usd' => 199.00,
                'billing_period' => 'monthly',
                'features' => [
                    'en' => [
                        'Unlimited Competitor Profiles',
                        'Real-Time High-Frequency Web Scrapes',
                        'Custom AI Agent Prompts & Fine-tuning',
                        'Isolated Multi-Tenant Database',
                        'Dedicated Technical Account Manager',
                        '24/7 Priority SLA Response',
                    ],
                    'ar' => [
                        'ملفات تعريف غير محدودة للمنافسين',
                        'فحص عالي التردد في الوقت الفعلي',
                        'تخصيص وكلاء الذكاء الاصطناعي والموجهات',
                        'قاعدة بيانات سحابية معزولة تماماً لكل مستأجر',
                        'مدير حساب تقني مخصص ومباشر',
                        'استجابة سريعة مع اتفاقية مستوى خدمة 24/7',
                    ],
                ],
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
