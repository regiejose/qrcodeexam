<?php

namespace Drupal\dafa_qr_code\Plugin\Block;

use Drupal\Core\Block\BlockBase;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Provides a QR Code Block
 *
 * @Block(
 *   id = "dafa_qrcode_block",
 *   admin_label = @Translation("Dafa QR Code"),
 *   category = @Translation("Dafa QR Code"),
 * )
 */
class QRCodeBlock extends BlockBase
{
  /**
   * Bacon Renderer Object.
   */
  protected $baconRenderer;

  /**
   * Request Object
   */
  protected $request;

  /**
   * Constructs a new SwitchUserBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   Current user.
   * @param \Drupal\Core\Entity\EntityStorageInterface $user_storage
   *   The user storage.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->baconRenderer = new ImageRenderer(
      new RendererStyle(400),
      new ImagickImageBackEnd()
    );

    $this->request = \Drupal::request();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }


  /**
   * {@inheritdoc}
   */
  public function build() 
  {
    $writer = new Writer($this->baconRenderer);
    $qr_image = base64_encode($writer->writeString($this->request->getUri()));

    $renderable = [
      '#theme' => 'dafa_qr_code',
      '#qrcodeUrl' => $qr_image,
      '#cache' => [
        'max-age' => 0,
      ]
    ];

    return $renderable;
  }
}