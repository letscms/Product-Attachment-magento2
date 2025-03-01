<?php

namespace Letscms\ProductAttachment\Ui\DataProvider;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Letscms\ProductAttachment\Model\ResourceModel\Attachment\CollectionFactory;

class File extends AbstractModifier
{
    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CollectionFactory
     */
    protected $attachmentCollectionFactory;

    public function __construct(
        ArrayManager $arrayManager,
        StoreManagerInterface $storeManager,
        CollectionFactory $attachmentCollectionFactory
    ) {
        $this->arrayManager = $arrayManager;
        $this->storeManager = $storeManager;
        $this->attachmentCollectionFactory = $attachmentCollectionFactory;
    }

    public function modifyMeta(array $meta)
    {
        $fieldCode = 'product_attachments_file';  
        $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(
            static::CONTAINER_PREFIX . $fieldCode,
            $meta,
            null,
            'children'
        );

        if (!$elementPath) {
            return $meta;
        }

        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $meta = $this->arrayManager->merge(
            $containerPath,
            $meta,
            [
                'children' => [
                    $fieldCode => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'elementTmpl' => 'Letscms_ProductAttachment/elements/file',
                                    'media_url' => $mediaUrl
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        );


        return $meta;
    }



    public function modifyData(array $data)
    {
        return $data;
    }
}
