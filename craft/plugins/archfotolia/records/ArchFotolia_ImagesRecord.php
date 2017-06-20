<?php
namespace Craft;

class ArchFotolia_ImagesRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'archfotolia_images';
    }

    protected function defineAttributes()
    {
        return array(
            'fotolia_id' => AttributeType::String,
            'category_id' => AttributeType::Number,
        );
    }
}