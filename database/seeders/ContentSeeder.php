<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContentType;
use App\Models\ContentField;
use App\Models\ContentEntry;
use App\Models\User;

class ContentSeeder extends Seeder
{
    /**
     * Seed sample content types with fields
     */
    public function run(): void
    {
        // ==========================================
        // BLOG POST CONTENT TYPE
        // ==========================================
        $blogPost = ContentType::create([
            'name' => 'blog-post',
            'display_name' => 'Blog Post',
            'description' => 'Standard blog post with title, content, author, and featured image',
            'icon' => 'document-text',
            'is_active' => true,
        ]);

        // Fields for Blog Post
        ContentField::create([
            'content_type_id' => $blogPost->id,
            'name' => 'title',
            'display_name' => 'Title',
            'type' => 'string',
            'description' => 'Post title',
            'is_required' => true,
            'order' => 1,
            'settings' => ['max_length' => 255],
        ]);

        ContentField::create([
            'content_type_id' => $blogPost->id,
            'name' => 'excerpt',
            'display_name' => 'Excerpt',
            'type' => 'text',
            'description' => 'Short summary',
            'is_required' => false,
            'order' => 2,
            'settings' => ['max_length' => 500],
        ]);

        ContentField::create([
            'content_type_id' => $blogPost->id,
            'name' => 'body',
            'display_name' => 'Body',
            'type' => 'rich_text',
            'description' => 'Main content',
            'is_required' => true,
            'order' => 3,
        ]);

        ContentField::create([
            'content_type_id' => $blogPost->id,
            'name' => 'author',
            'display_name' => 'Author',
            'type' => 'string',
            'description' => 'Author name',
            'is_required' => true,
            'order' => 4,
        ]);

        ContentField::create([
            'content_type_id' => $blogPost->id,
            'name' => 'featured_image',
            'display_name' => 'Featured Image',
            'type' => 'url',
            'description' => 'Post thumbnail',
            'is_required' => false,
            'order' => 5,
        ]);

        ContentField::create([
            'content_type_id' => $blogPost->id,
            'name' => 'published_date',
            'display_name' => 'Published Date',
            'type' => 'date',
            'description' => 'Original publish date',
            'is_required' => false,
            'order' => 6,
        ]);

        ContentField::create([
            'content_type_id' => $blogPost->id,
            'name' => 'is_featured',
            'display_name' => 'Featured Post',
            'type' => 'boolean',
            'description' => 'Show on homepage',
            'is_required' => false,
            'order' => 7,
        ]);

        // ==========================================
        // PRODUCT CONTENT TYPE
        // ==========================================
        $product = ContentType::create([
            'name' => 'product',
            'display_name' => 'Product',
            'description' => 'E-commerce product with pricing and inventory',
            'icon' => 'shopping-bag',
            'is_active' => true,
        ]);

        ContentField::create([
            'content_type_id' => $product->id,
            'name' => 'name',
            'display_name' => 'Product Name',
            'type' => 'string',
            'is_required' => true,
            'order' => 1,
        ]);

        ContentField::create([
            'content_type_id' => $product->id,
            'name' => 'description',
            'display_name' => 'Description',
            'type' => 'rich_text',
            'is_required' => true,
            'order' => 2,
        ]);

        ContentField::create([
            'content_type_id' => $product->id,
            'name' => 'price',
            'display_name' => 'Price',
            'type' => 'number',
            'is_required' => true,
            'order' => 3,
            'settings' => ['min' => 0, 'step' => 0.01],
        ]);

        ContentField::create([
            'content_type_id' => $product->id,
            'name' => 'sku',
            'display_name' => 'SKU',
            'type' => 'string',
            'is_required' => true,
            'is_unique' => true,
            'order' => 4,
        ]);

        ContentField::create([
            'content_type_id' => $product->id,
            'name' => 'in_stock',
            'display_name' => 'In Stock',
            'type' => 'boolean',
            'is_required' => false,
            'order' => 5,
        ]);

        ContentField::create([
            'content_type_id' => $product->id,
            'name' => 'category',
            'display_name' => 'Category',
            'type' => 'string',
            'is_required' => false,
            'order' => 6,
        ]);

        // ==========================================
        // FAQ CONTENT TYPE
        // ==========================================
        $faq = ContentType::create([
            'name' => 'faq',
            'display_name' => 'FAQ',
            'description' => 'Frequently asked questions',
            'icon' => 'question-mark-circle',
            'is_active' => true,
        ]);

        ContentField::create([
            'content_type_id' => $faq->id,
            'name' => 'question',
            'display_name' => 'Question',
            'type' => 'string',
            'is_required' => true,
            'order' => 1,
        ]);

        ContentField::create([
            'content_type_id' => $faq->id,
            'name' => 'answer',
            'display_name' => 'Answer',
            'type' => 'text',
            'is_required' => true,
            'order' => 2,
        ]);

        ContentField::create([
            'content_type_id' => $faq->id,
            'name' => 'category',
            'display_name' => 'Category',
            'type' => 'string',
            'is_required' => false,
            'order' => 3,
        ]);

        ContentField::create([
            'content_type_id' => $faq->id,
            'name' => 'order',
            'display_name' => 'Display Order',
            'type' => 'number',
            'is_required' => false,
            'order' => 4,
        ]);

        // ==========================================
        // SAMPLE CONTENT ENTRIES
        // ==========================================
        $admin = User::where('email', 'admin@contentra.test')->first();

        if ($admin) {
            // Sample Blog Post
            ContentEntry::create([
                'content_type_id' => $blogPost->id,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'slug' => 'getting-started-with-contentra',
                'status' => 'published',
                'published_at' => now(),
                'data' => [
                    'title' => 'Getting Started with Contentra CMS',
                    'excerpt' => 'Learn how to use Contentra to build modern headless applications.',
                    'body' => '<h2>Welcome to Contentra!</h2><p>This is your first blog post. Contentra is a powerful headless CMS built with Laravel...</p>',
                    'author' => 'Admin User',
                    'featured_image' => 'https://placehold.co/800x400',
                    'published_date' => now()->format('Y-m-d'),
                    'is_featured' => true,
                ],
            ]);

            // Sample Product
            ContentEntry::create([
                'content_type_id' => $product->id,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'slug' => 'premium-subscription',
                'status' => 'published',
                'published_at' => now(),
                'data' => [
                    'name' => 'Premium Subscription',
                    'description' => '<p>Get access to all premium features including AI-powered content generation.</p>',
                    'price' => 29.99,
                    'sku' => 'SUB-PREMIUM-001',
                    'in_stock' => true,
                    'category' => 'Subscriptions',
                ],
            ]);

            // Sample FAQ
            ContentEntry::create([
                'content_type_id' => $faq->id,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'slug' => 'what-is-contentra',
                'status' => 'published',
                'published_at' => now(),
                'data' => [
                    'question' => 'What is Contentra?',
                    'answer' => 'Contentra is an open-source headless CMS built on Laravel, designed for modern web and mobile applications.',
                    'category' => 'General',
                    'order' => 1,
                ],
            ]);
        }

        $this->command->info('✅ Content types, fields, and sample entries created!');
        
    }
}