<?php
/**
 * Class responsible for generating the display of the Image Capcta on the enabled form.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
	
class C4WP_Create_Image_Captcha {

    // Configuration Options
	public $c4wp_image_width;
	public $c4wp_image_height;
	public $c4wp_fonts;
	public $c4wp_char_on_image;
	public $c4wp_random_dots;
	public $c4wp_random_lines;
	public $c4wp_text_color;
	public $c4wp_noice_color;
	public $c4wp_possible_letters;
    
	/**
	 * Initialize the class and set its properties.
	 */
    public function __construct( $c4wp_paramenters ) {
        
        $this->c4wp_image_width 		=  $c4wp_paramenters['c4wp_image_width'];
		$this->c4wp_image_height 		=  $c4wp_paramenters['c4wp_image_height'];
		$this->c4wp_fonts 				=  $c4wp_paramenters['c4wp_fonts'];
		$this->c4wp_char_on_image 		=  $c4wp_paramenters['c4wp_char_on_image'];
		$this->c4wp_random_dots 		=  $c4wp_paramenters['c4wp_random_dots'];
		$this->c4wp_random_lines 		=  $c4wp_paramenters['c4wp_random_lines'];
		$this->c4wp_text_color 			=  $c4wp_paramenters['c4wp_text_color'];
		$this->c4wp_noice_color 		=  $c4wp_paramenters['c4wp_noice_color'];
		$this->c4wp_possible_letters 	=  $c4wp_paramenters['c4wp_possible_letters'];
        
        if ( ! extension_loaded( 'gd' ) ) {
            
			return FALSE;
        }
    }
    
	/**
	 * The Functions Create captcha image for the plugin. 
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
    public function createCaptcha( ){
        
		$c4wp_return_words = '';
		
		$i = 0;
		while( $i < $this->c4wp_char_on_image ) { 
		
			$c4wp_return_words .= substr( $this->c4wp_possible_letters, mt_rand( 0, strlen( $this->c4wp_possible_letters )-1 ), 1 );
			$i++;
		}
		
		$c4wp_font_size = $this->c4wp_image_height * 0.55;
		$c4wp_image = @imagecreate( $this->c4wp_image_width, $this->c4wp_image_height );
		
		// setting the background, text and noise colours here
		$c4wp_background_color = imagecolorallocate( $c4wp_image, 255, 255, 255 );
		$c4wp_arr_text_color = $this->hexrgb( $this->c4wp_text_color );
		$c4wp_text_color = imagecolorallocate( $c4wp_image, $c4wp_arr_text_color['red'], $c4wp_arr_text_color['green'], $c4wp_arr_text_color['blue'] );
		
		$c4wp_arr_noice_color = $this->hexrgb( $this->c4wp_noice_color );
		$c4wp_noice_color = imagecolorallocate($c4wp_image, $c4wp_arr_noice_color['red'], 
		$c4wp_arr_noice_color['green'], $c4wp_arr_noice_color['blue']);
		
		// generating the dots randomly in background 
		for( $i = 0; $i < $this->c4wp_random_dots; $i++ ) {
		
			imagefilledellipse($c4wp_image, mt_rand(0,$this->c4wp_image_width), mt_rand(0,$this->c4wp_image_height), 2, 3, $c4wp_noice_color);
		}
		
		// generating lines randomly in background of image 
		for( $i = 0; $i < $this->c4wp_random_lines; $i++ ) {
		
				imageline( $c4wp_image, mt_rand( 0, $this->c4wp_image_width ), mt_rand( 0, $this->c4wp_image_height ), mt_rand( 0, $this->c4wp_image_width ), mt_rand( 0,$this->c4wp_image_height ), $c4wp_noice_color );
		}
		
		// create a text box and add 6 letters code in it 
		$textbox = imagettfbbox( $c4wp_font_size, 0, $this->c4wp_fonts, $c4wp_return_words ); 
		$x = ( $this->c4wp_image_width - $textbox[4] ) / 2;
		$y = ( $this->c4wp_image_height - $textbox[5] ) / 2;
		imagettftext( $c4wp_image, $c4wp_font_size, 0, $x, $y, $c4wp_text_color, $this->c4wp_fonts , $c4wp_return_words );
		
		// Show captcha image in the page html page
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );
		header( 'Content-Type: image/jpeg' );
		imagejpeg($c4wp_image,NULL,90);
        imagedestroy($c4wp_image);
		$_SESSION['c4wp_random_input_captcha'][] = $c4wp_return_words;
    }
	
	/**
	 * The Functions return hexrgb color 
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function hexrgb( $hexstr ) {

	  $c4wp_int = hexdec( $hexstr );
	
	  return array( 'red' 	=> 0xFF & ( $c4wp_int >> 0x10 ),
					'green' => 0xFF & ( $c4wp_int >> 0x8 ),
					'blue' 	=> 0xFF & $c4wp_int );
	}
}	