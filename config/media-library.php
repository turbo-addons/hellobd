<?php

return [

    /*
     * The disk on which to store added files and derived images by default. Choose
     * one or more of the disks you've configured in config/filesystems.php.
     */
    'disk_name' => env('MEDIA_DISK', 'public'),

    /*
     * The disk on which to store temporary uploads.
     * This should be a local disk.
     */
    'temporary_upload_disk' => env('MEDIA_TEMPORARY_DISK', 'local'),

    /*
     * The maximum file size of an item in bytes.
     * Adding a larger file will result in an exception.
     */
    'max_file_size' => 1024 * 1024 * 10, // 10MB

    /*
     * This queue connection will be used to generate derived and responsive images.
     * Leave empty to use the default queue connection.
     */
    'queue_connection_name' => env('QUEUE_CONNECTION', 'sync'),

    /*
     * This queue will be used to generate derived and responsive images.
     * Leave empty to use the default queue.
     */
    'queue_name' => '',

    /*
     * By default all conversions will be performed on a queue.
     */
    'queue_conversions_by_default' => env('MEDIA_LIBRARY_QUEUE_CONVERSIONS', true),

    /*
     * The fully qualified class name of the media model.
     */
    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    /*
     * The fully qualified class name of the model used for temporary uploads.
     */
    'temporary_upload_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    /*
     * When enabled, media collections will be serialised using the default
     * laravel model serialisation behaviour.
     *
     * Keep this disabled if using Media Library Pro components (https://medialibrary.pro)
     */
    'use_default_collection_serialization' => false,

    /*
     * The fully qualified class name of the media repository.
     */
    'media_repository' => Spatie\MediaLibrary\MediaCollections\MediaRepository::class,

    'remote' => [
        /*
         * Any extra headers that should be included when uploading media to
         * a remote disk. Even though supported headers may vary between
         * different drivers, a sensible default has been provided.
         *
         * Supported by S3: CacheControl, Expires, StorageClass,
         * ServerSideEncryption, Metadata, ACL, ContentEncoding
         */
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
        ],
    ],

    'responsive_images' => [
        /*
         * This class is responsible for calculating the target widths of the responsive
         * images. By default we optimize for filesize and create variations that each are 30%
         * smaller than the previous one. More info in the documentation.
         *
         * https://docs.spatie.be/laravel-medialibrary/v9/advanced-usage/generating-responsive-images
         */
        'width_calculator' => Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\FileSizeOptimizedWidthCalculator::class,

        /*
         * By default rendering media to a responsive image will add some javascript and a tiny placeholder.
         * This ensures that the browser can already determine the correct layout.
         */
        'use_tiny_placeholders' => true,

        /*
         * This class will generate the tiny placeholder used for progressive image loading. By default
         * the media library will use a tiny blurred jpg image.
         */
        'tiny_placeholder_generator' => Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\Blurred::class,
    ],

    /*
     * When urls to files get generated, this class will be called. Use the default
     * if your files are stored locally above the site root or on s3.
     */
    'url_generator' => Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator::class,

    /*
     * Moves media on updating to keep path consistent. Enable it only with a custom
     * PathGenerator that uses media data (e.g. title) as foldername.
     */
    'moves_media_on_update' => false,

    /*
     * Whether to activate versioning when urls to files get generated.
     * When activated, this attaches a ?v=xx query string to the URL.
     */
    'version_urls' => false,

    /*
     * The class that contains the strategy for determining a media file's path.
     */
    'path_generator' => Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator::class,

    /*
     * Here you can specify which path generator should be used for the given class.
     */
    'custom_path_generators' => [
        // Model::class => PathGenerator::class
        // or
        // 'model_morph_alias' => PathGenerator::class
    ],

    /*
     * When urls to files get generated, this class will be called. Use the default
     * if your files are stored locally above the site root or on s3.
     */
    'default_loading_attribute_value' => null,

    /*
     * You can specify a prefix for that is used for storing all media.
     * If you set this to `/my-subdir`, all your media will be stored in a `/my-subdir` directory.
     */
    'prefix' => env('MEDIA_PREFIX', ''),
];
