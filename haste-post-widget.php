<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Haste_Post_Widget extends Widget_Base {
	public function get_name() {
		return 'haste-posts-widget';
	}

	public function get_title() {
		return __( 'Haste Posts', 'haste-posts-widgets' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-post-list';
	}

	/**
	* Retrieve button sizes.
	*
	* @access public
	* @static
	*
	* @return array An array containing button sizes.
	*/
	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'elementor' ),
			'sm' => __( 'Small', 'elementor' ),
			'md' => __( 'Medium', 'elementor' ),
			'lg' => __( 'Large', 'elementor' ),
			'xl' => __( 'Extra Large', 'elementor' ),
		];
	}


	protected function _register_controls() {

		/**
		* Posts options
		* CONTENT TAB
		*/

		//
		$this->start_controls_section(
			'section_query',
			[
				'label' 	=> esc_html__( 'Posts query', 'haste-elementor-widgets' ),
			]
		);

		$this->add_control(
			'element_title',
			[
				'label' 	=> __( 'Title', 'haste-elementor-widgets' ),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> '',
			]
		);

		$this->add_control(
			'query_options',
			[
				'label' => __( 'Query Options', 'haste-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' 	=> __( 'Number of Posts', 'haste-elementor-widgets' ),
				'type' 		=>  Controls_Manager::NUMBER,
				'default' 	=> 5,
				'min'     	=> 1,
				'max'     	=> 20,
			]
		);

		// Post types
		$args = array(
			'public'   => true,
			'publicly_queryable' => true,
		);

		$output = 'objects'; // 'names' or 'objects' (default: 'names')
		$operator = 'and'; // 'and' or 'or' (default: 'and')

		$post_types = get_post_types( $args, $output, $operator );
		$post_types_list = array();

		foreach( $post_types as $post_type ) {
			$post_types_list[ $post_type->name ] = $post_type->labels->singular_name;
		}

		$this->add_control(
			'post_type',
			[
				'label' 	=> __( 'Post type', 'haste-elementor-widgets' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> $post_types_list,
			]
		);

		// Categories
		$categories = get_categories( array(
			'orderby' => 'name',
			'order'   => 'ASC'
		)
	);

	$cat_list = array();

	foreach( $categories as $category ) {
		$cat_list[ $category->slug ] = $category->name;
	}

	$this->add_control(
		'categories',
		[
			'label' 	=> __( 'Categories', 'haste-elementor-widgets' ),
			'type' 		=> Controls_Manager::SELECT2,
			'options' 	=> $cat_list,
			'multiple' 	=> true,
		]
	);

	// Tags
	$tags = get_tags( array(
		'orderby' => 'name',
		'order'   => 'ASC'
	)
);

$tags_list = array();

foreach( $tags as $tag ) {
	$tags_list[ $tag->slug ] = $tag->name;
}

$this->add_control(
	'tags',
	[
		'label' 	=> __( 'Tags', 'haste-elementor-widgets' ),
		'type' 		=> Controls_Manager::SELECT2,
		'options' 	=> $tags_list,
		'multiple' 	=> true,
	]
);

$this->end_controls_section();


// Post elements display options
$this->start_controls_section(
	'section_post_elements',
	[
		'label' 	=> esc_html__( 'Post Elements', 'haste-elementor-widgets' ),
	]
);

$this->add_control(
	'show_post_image',
	[
		'label' => __( 'Show Post Featured Image', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::SWITCHER,
		'default' => '',
		'label_on' => __( 'Show', 'haste-elementor-widgets' ),
		'label_off' => __( 'Hide', 'haste-elementor-widgets' ),
		'return_value' => true,
	]
);

$this->add_group_control(
	Group_Control_Image_Size::get_type(),
	[
		'name' => 'image', // Actually its `image_size`.
		'label' => __( 'Image Size', 'elementor' ),
		'default' => 'large',
	]
);

$this->add_control(
	'post_title_tag',
	[
		'label' 	=> __( 'Post Title HTML Tag', 'haste-elementor-widgets' ),
		'type' 		=> Controls_Manager::SELECT,
		'default' 	=> 'h3',
		'options' 	=> array(
			'h1' 		=> __( 'H1' , 'haste-elementor-widgets' ),
			'h2' 		=> __( 'H2' , 'haste-elementor-widgets' ),
			'h3' 		=> __( 'H3' , 'haste-elementor-widgets' ),
			'h4' 		=> __( 'H4' , 'haste-elementor-widgets' ),
			'p' 		=> __( 'Paragraph' , 'haste-elementor-widgets' ),
		),
	]
);

$this->add_control(
	'show_meta',
	[
		'label' 	=> __( 'Show post meta information', 'haste-elementor-widgets' ),
		'type' 		=> Controls_Manager::SELECT2,
		'options' 	=> [
			'date' 		=> __( 'Date', 'haste-elementor-widgets' ),
			'author' 	=> __( 'Author', 'haste-elementor-widgets' ),
			'category' 	=> __( 'Category', 'haste-elementor-widgets' ),
			'comments' 	=> __( 'Comments Link', 'haste-elementor-widgets' ),
		],
		'multiple' => true,
	]
);

$this->add_control(
	'show_excerpt',
	[
		'label' => __( 'Show Post Excerpt', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::SWITCHER,
		'default' => true,
		'label_on' => __( 'Show', 'haste-elementor-widgets' ),
		'label_off' => __( 'Hide', 'haste-elementor-widgets' ),
		'return_value' => true,
	]
);

$this->add_control(
	'show_read_more',
	[
		'label' => __( 'Show "Read more" link', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::SWITCHER,
		'default' => true,
		'label_on' => __( 'Show', 'haste-elementor-widgets' ),
		'label_off' => __( 'Hide', 'haste-elementor-widgets' ),
		'return_value' => true,
	]
);

$this->end_controls_section();

/**
* Visual Settings
* TAB STYLE
*/

// Posts styles
$this->start_controls_section(
	'section_posts_style',
	[
		'label' 	=> esc_html__( 'Posts styles', 'haste-elementor-widgets' ),
		'tab'   => Controls_Manager::TAB_STYLE,

	]
);

$this->add_responsive_control(
	'post_columns',
	[
		'label' 	=> __( 'Post layout', 'haste-elementor-widgets' ),
		'type' 		=> Controls_Manager::SELECT,
		'default'	=> 1,
			array(
				'cols' 		=> 1,
				),
		'options' 	=> array(
			1	 		=> __( 'List' , 'haste-elementor-widgets' ),
			2			=> __( '2 columns grid', 'haste-elementor-widgets' ),
			3			=> __( '3 columns grid', 'haste-elementor-widgets' ),
			4			=> __( '4 columns grid', 'haste-elementor-widgets' ),
		),
		'selectors' => [
			'{{WRAPPER}} .haste-post-item' => 'width: calc( ( 100% - ( ( {{post_margin.LEFT}}{{post_margin.UNIT}} + {{post_margin.RIGHT}}{{post_margin.UNIT}} ) * {{COLS}} ) ) / {{COLS}})',
		],
	]
);

$this->add_control(
	'posts_bg_color',
	[
		'label' => __( 'Post Background Color', 'haste-elementor-widgets' ),
		'type' 	=> Controls_Manager::COLOR,
		'default' => 'transparent',
		'scheme'=> [
			'type' 	=> Scheme_Color::get_type(),
			'value'	=> Scheme_Color::COLOR_1,
		],
		'selectors' => [
			'{{WRAPPER}} .haste-post-item' => 'background-color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'post_align',
	[
		'label' => __( 'Alignment', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::CHOOSE,
		'options' => [
			'left' => [
				'title' => __( 'Left', 'haste-elementor-widgets' ),
				'icon' => 'fa fa-align-left',
			],
			'center' => [
				'title' => __( 'Center', 'haste-elementor-widgets' ),
				'icon' => 'fa fa-align-center',
			],
			'right' => [
				'title' => __( 'Right', 'haste-elementor-widgets' ),
				'icon' => 'fa fa-align-right',
			],
			'justify' => [
				'title' => __( 'Justified', 'haste-elementor-widgets' ),
				'icon' => 'fa fa-align-justify',
			],
		],
		'default' => '',
		'selectors' => [
			'{{WRAPPER}} .haste-post-item' => 'text-align: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'post_padding',
	[
		'label' => __( 'Post Padding', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
		'default' => array(
			'top' 		=> 0,
			'right' 	=> 0,
			'bottom' 	=> 0,
			'left' 	=> 0,
		),
		'selectors' => [
			'{{WRAPPER}} .haste-post-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'post_margin',
	[
		'label' => __( 'Post Margin', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
		'default' => array(
			'top' 		=> 0,
			'right' 	=> 0,
			'bottom' 	=> 45,
			'left' 	=> 0,
		),
		'selectors' => [
			'{{WRAPPER}} .haste-post-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .haste-posts-loop' => 'margin: -{{TOP}}{{UNIT}} -{{RIGHT}}{{UNIT}} -{{BOTTOM}}{{UNIT}} -{{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name' => 'post_border',
		'label' => __( 'Border', 'haste-elementor-widgets' ),
		'placeholder' => '1px',
		'default' => '1px',
		'selector' => '{{WRAPPER}} .haste-post-item',
		'separator' => 'before',
	]
);

$this->add_control(
	'border_radius',
	[
		'label' => __( 'Border Radius', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors' => [
			'{{WRAPPER}} .haste-post-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name' => 'post_box_shadow',
		'selector' => '{{WRAPPER}} .haste-post-item',
	]
);


$this->end_controls_section();


// Post title
$this->start_controls_section(
	'section_titles_styles',
	[
		'label' 	=> esc_html__( 'Titles', 'haste-elementor-widgets' ),
		'tab'   => Controls_Manager::TAB_STYLE,

	]
);

$this->add_control(
	'posts_title_color',
	[
		'label' => __( 'Post Title Color', 'haste-elementor-widgets' ),
		'type' 	=> Controls_Manager::COLOR,
		'scheme'=> [
			'type' 	=> Scheme_Color::get_type(),
			'value'	=> Scheme_Color::COLOR_1,
		],
		'selectors' => [
			'{{WRAPPER}} .haste-post-title' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'post_title_margin',
	[
		'label' => __( 'Post Title Margin', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
		'selectors' => [
			'{{WRAPPER}} .haste-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name' => 'post_title_typography',
		'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		'selector' => '{{WRAPPER}} .haste-post-title',
	]
);

$this->end_controls_section();


// Text
$this->start_controls_section(
	'section_text_styles',
	[
		'label' 	=> esc_html__( 'Text', 'haste-elementor-widgets' ),
		'tab'   => Controls_Manager::TAB_STYLE,

	]
);

$this->add_control(
	'posts_text_color',
	[
		'label' => __( 'Text Color', 'haste-elementor-widgets' ),
		'type' 	=> Controls_Manager::COLOR,
		'default' => '#000',
		'scheme'=> [
			'type' 	=> Scheme_Color::get_type(),
			'value'	=> Scheme_Color::COLOR_1,
		],
		'selectors' => [
			'{{WRAPPER}} .haste-post-item' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'posts_link_color',
	[
		'label' => __( 'Link Color', 'haste-elementor-widgets' ),
		'type' 	=> Controls_Manager::COLOR,
		'scheme'=> [
			'type' 	=> Scheme_Color::get_type(),
			'value'	=> Scheme_Color::COLOR_1,
		],
		'selectors' => [
			'{{WRAPPER}} .haste-post-item a' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_section();


// Image styles
$this->start_controls_section(
	'section_image_styles',
	[
		'label' 	=> esc_html__( 'Featured Image', 'haste-elementor-widgets' ),
		'tab'   => Controls_Manager::TAB_STYLE,

	]
);

$this->add_control(
	'post_image_position',
	[
		'label' 	=> __( 'Image position', 'haste-elementor-widgets' ),
		'type' 		=> Controls_Manager::SELECT,
		'default' 	=> 'image-top',
		'options' 	=> array(
			'image-top' 	=> __( 'Top' , 'haste-elementor-widgets' ),
			'image-left' 	=> __( 'Left' , 'haste-elementor-widgets' ),
			'image-right' 	=> __( 'Right' , 'haste-elementor-widgets' ),
		),
	]
);


$this->add_responsive_control(
	'post_image_width',
	[
		'label' => __( 'Image Width (%)', 'elementor' ),
		'type' => Controls_Manager::SLIDER,
		'default' => [
			'size' => 100,
			'unit' => '%',
		],
		'tablet_default' => [
			'unit' => '%',
		],
		'mobile_default' => [
			'unit' => '%',
		],
		'size_units' => [ '%' ],
		'range' => [
			'%' => [
				'min' => 1,
				'max' => 100,
			],
		],
		'selectors' => [
			'{{WRAPPER}} .haste-post-thumbnail' => 'width: {{SIZE}}%;',
			'{{WRAPPER}} .haste-image-left + .haste-post-wrapper, {{WRAPPER}} .haste-image-right + .haste-post-wrapper' => 'width: calc( 100% - {{SIZE}}% );',
		],
	]
);

$this->add_responsive_control(
	'post_thumb_margin',
	[
		'label' => __( 'Image Margin', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
		'selectors' => [
			'{{WRAPPER}} .haste-post-thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'post_thumb_padding',
	[
		'label' => __( 'Image Padding', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
		'selectors' => [
			'{{WRAPPER}} .haste-post-thumbnail img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();


// Meta styles
$this->start_controls_section(
	'section_meta_styles',
	[
		'label' 	=> esc_html__( 'Meta Informations', 'haste-elementor-widgets' ),
		'tab'   => Controls_Manager::TAB_STYLE,

	]
);

$this->add_control(
	'meta_font_size',
	[
		'label' => __( 'Font Size', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::SLIDER,
		'default' => [
			'size' => 12,
		],
		'range' => [
			'px' => [
				'min' => 6,
				'max' => 60,
				'step' => 1,
			],
			'em' => [
				'min' => 0.1,
				'max' => 20,
				'step' => 0.1,
			],
		],
		'size_units' => [ 'px', 'em' ],
		'selectors' => [
			'{{WRAPPER}} .haste-post-meta' => 'font-size: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'meta_margin',
	[
		'label' => __( 'Margin', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
		'selectors' => [
			'{{WRAPPER}} .haste-post-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'meta_space_between',
	[
		'label' => __( 'Space Between', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::SLIDER,
		'default' => [
			'size' => 10,
		],
		'range' => [
			'px' => [
				'min' => 0,
				'max' => 100,
				'step' => 1,
			],
			'em' => [
				'min' => 0.1,
				'max' => 20,
				'step' => 0.1,
			],
		],
		'size_units' => [ 'px', 'em' ],
		'selectors' => [
			'{{WRAPPER}} .haste-meta-info' => 'margin-right: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'meta_color',
	[
		'label' => __( 'Color', 'haste-elementor-widgets' ),
		'type' 	=> Controls_Manager::COLOR,
		'scheme'=> [
			'type' 	=> Scheme_Color::get_type(),
			'value'	=> Scheme_Color::COLOR_1,
		],
		'selectors' => [
			'{{WRAPPER}} .haste-post-meta' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'meta_icons_color',
	[
		'label' => __( 'Icon Color', 'haste-elementor-widgets' ),
		'type' 	=> Controls_Manager::COLOR,
		'scheme'=> [
			'type' 	=> Scheme_Color::get_type(),
			'value'	=> Scheme_Color::COLOR_1,
		],
		'selectors' => [
			'{{WRAPPER}} .haste-post-meta .fa' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_section();


// Read more styles
$this->start_controls_section(
	'section_read_more_styles',
	[
		'label' 	=> esc_html__( 'Read more link', 'haste-elementor-widgets' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_control(
	'read_more_button_type',
	[
		'label' => __( 'Type', 'elementor' ),
		'type' => Controls_Manager::SELECT,
		'default' => '',
		'options' => [
			'' => __( 'Default', 'elementor' ),
			'info' => __( 'Info', 'elementor' ),
			'success' => __( 'Success', 'elementor' ),
			'warning' => __( 'Warning', 'elementor' ),
			'danger' => __( 'Danger', 'elementor' ),
		],
		'prefix_class' => 'elementor-button-',
	]
);

$this->add_control(
	'read_more_text',
	[
		'label' => __( 'Text', 'elementor' ),
		'type' => Controls_Manager::TEXT,
		'default' => __( 'Click me', 'elementor' ),
		'placeholder' => __( 'Click me', 'elementor' ),
	]
);

$this->add_responsive_control(
	'read_more_align',
	[
		'label' => __( 'Alignment', 'elementor' ),
		'type' => Controls_Manager::CHOOSE,
		'options' => [
			'left'    => [
				'title' => __( 'Left', 'elementor' ),
				'icon' => 'fa fa-align-left',
			],
			'center' => [
				'title' => __( 'Center', 'elementor' ),
				'icon' => 'fa fa-align-center',
			],
			'right' => [
				'title' => __( 'Right', 'elementor' ),
				'icon' => 'fa fa-align-right',
			],
			'justify' => [
				'title' => __( 'Justified', 'elementor' ),
				'icon' => 'fa fa-align-justify',
			],
		],
		'default' => '',
		'selectors' => [
			'{{WRAPPER}} .elementor-button-wrapper' => 'text-align: {{VALUE}};',
		],
		'prefix_class' => 'elementor%s-align-',
	]
);

$this->add_control(
	'read_more_size',
	[
		'label' => __( 'Size', 'elementor' ),
		'type' => Controls_Manager::SELECT,
		'default' => 'sm',
		'options' => self::get_button_sizes(),
	]
);

$this->add_control(
	'read_more_icon',
	[
		'label' => __( 'Icon', 'elementor' ),
		'type' => Controls_Manager::ICON,
		'label_block' => true,
		'default' => '',
	]
);

$this->add_control(
	'read_more_icon_align',
	[
		'label' => __( 'Icon Position', 'elementor' ),
		'type' => Controls_Manager::SELECT,
		'default' => 'left',
		'options' => [
			'left' => __( 'Before', 'elementor' ),
			'right' => __( 'After', 'elementor' ),
		],
		'condition' => [
			'icon!' => '',
		],
	]
);

$this->add_control(
	'read_more_icon_indent',
	[
		'label' => __( 'Icon Spacing', 'elementor' ),
		'type' => Controls_Manager::SLIDER,
		'range' => [
			'px' => [
				'max' => 50,
			],
		],
		'condition' => [
			'icon!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
		],
	]
);


$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name' => 'read_more_typography',
		'label' => __( 'Typography', 'elementor' ),
		'scheme' => Scheme_Typography::TYPOGRAPHY_4,
		'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
	]
);


$this->start_controls_tabs( 'tabs_button_style' );

$this->start_controls_tab(
	'tab_button_normal',
	[
		'label' => __( 'Normal', 'elementor' ),
	]
);

$this->add_control(
	'read_more_button_text_color',
	[
		'label' => __( 'Text Color', 'elementor' ),
		'type' => Controls_Manager::COLOR,
		'default' => '#ffffff',
		'selectors' => [
			'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'read_more_background_color',
	[
		'label' => __( 'Background Color', 'elementor' ),
		'type' => Controls_Manager::COLOR,
		'scheme' => [
			'type' => Scheme_Color::get_type(),
			'value' => Scheme_Color::COLOR_4,
		],
		'selectors' => [
			'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'tab_button_hover',
	[
		'label' => __( 'Hover', 'elementor' ),
	]
);

$this->add_control(
	'read_more_hover_color',
	[
		'label' => __( 'Text Color', 'elementor' ),
		'type' => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'read_more_button_background_hover_color',
	[
		'label' => __( 'Background Color', 'elementor' ),
		'type' => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'read_more_button_hover_border_color',
	[
		'label' => __( 'Border Color', 'elementor' ),
		'type' => Controls_Manager::COLOR,
		'condition' => [
			'border_border!' => '',
		],
		'selectors' => [
			'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'read_more_hover_animation',
	[
		'label' => __( 'Animation', 'elementor' ),
		'type' => Controls_Manager::HOVER_ANIMATION,
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->add_responsive_control(
	'read_more_margin',
	[
		'label' => __( 'Margin', 'haste-elementor-widgets' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
		'selectors' => [
			'{{WRAPPER}} .elementor-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name' => 'read_more_border',
		'label' => __( 'Border', 'elementor' ),
		'placeholder' => '1px',
		'default' => '1px',
		'selector' => '{{WRAPPER}} .elementor-button',
		'separator' => 'before',
	]
);

$this->add_control(
	'read_more_border_radius',
	[
		'label' => __( 'Border Radius', 'elementor' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors' => [
			'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name' => 'read_more_button_box_shadow',
		'selector' => '{{WRAPPER}} .elementor-button',
	]
);

$this->add_control(
	'read_more_text_padding',
	[
		'label' => __( 'Text Padding', 'elementor' ),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors' => [
			'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'separator' => 'before',
	]
);

$this->end_controls_section();
}

protected function render() {
	$settings = $this->get_settings();

	// get our input from the widget settings.
	$title 				= ! empty( $settings['element_title'] ) ? $settings['element_title'] : '';
	$post_type 			= ! empty( $settings['post_type'] ) ? $settings['post_type'] : '';
	$post_count 		= ! empty( $settings['posts_per_page'] ) ? (int)$settings['posts_per_page'] : 5;
	$post_categories 	= ! empty( $settings['categories'] ) ? $settings['categories'] : array();
	$post_tags 			= ! empty( $settings['tags'] ) ? $settings['tags'] : array();
	$post_layout 		= ! empty( $settings['post_layout'] ) ? $settings['post_layout'] : 1;
	$post_title_tag		= ! empty( $settings['post_title_tag'] ) ? $settings['post_title_tag'] : 'h3';
	$show_post_image	= ! empty( $settings['show_post_image'] ) ? $settings['show_post_image'] : true;
	$show_meta			= ! empty( $settings['show_meta'] ) ? $settings['show_meta'] : '';
	$show_excerpt		= ! empty( $settings['show_excerpt'] ) ? $settings['show_excerpt'] : true;
	?>

	<h2><?php echo esc_html( $title ); ?></h2>

	<?php


	$categories_terms = array();
	foreach ( $post_categories as $my_category ) {
		array_push( $categories_terms, $my_category );
	}


	$tags_terms = array();
	foreach ( $post_tags as $my_tag ) {
		array_push( $tags_terms, $my_tag );
	}

	$my_tax_query = array(
		'relation' => 'AND',
		array(
			'taxonomy' 	=> 'category',
			'field'    	=> 'slug',
			'terms'    	=> $categories_terms,
		),
		array(
			'taxonomy' => 'tag',
			'field'    => 'slug',
			'terms'    => $tags_terms,
		),
	);

	$args = array(
		'post_type'  => $post_type,
		'posts_per_page'=> $post_count,
		//'tax_query'		=> $my_tax_query,
	);

	// The Query
	$the_query = new \WP_Query( $args );
	$settings = $this->get_settings();

	echo Group_Control_Image_Size::get_attachment_image_html( $settings );

	// The Loop
	if ( $the_query->have_posts() ) { ?>

		<div class="haste-posts-loop layout-<?php echo $this->get_settings( 'post_layout' ); ?>">

			<?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('haste-post-item'); ?>>

					<?php if( $this->get_settings( 'show_post_image' ) == true && has_post_thumbnail() == true ) : ?>
						<div class="haste-post-thumbnail haste-<?php echo $this->get_settings( 'post_image_position' ); ?>">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="entry-link">
								<?php the_post_thumbnail( $this->get_settings( 'image_size' ) ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="haste-post-wrapper">
						<header class="haste-post-header">
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="entry-link">
								<?php $title_tag = $this->get_settings( 'post_title_tag' ); ?>
								<<?php echo $title_tag; ?> class="haste-post-title entry-title">
								<?php the_title(); ?>
								</<?php echo $title_tag; ?>>
							</a>
						</header>

						<?php $meta = $this->get_settings( 'show_meta' );

						if( !empty( $meta ) ) : ?>
						<div class="haste-post-meta entry-meta">
							<?php if( in_array( 'date', $meta ) ) : ?>
								<span class="haste-meta-info haste-post-date"><i class="fa fw fa-calendar" aria-hidden="true"></i> <?php echo '<time class="entry-date meta" datetime="'. esc_attr( get_the_date( 'c' ) ) .'">' . esc_html( get_the_date() ) . '</time>'; ?></span>
							<?php endif; ?>

							<?php if( in_array( 'author', $meta ) ) : ?>
								<span class="haste-meta-info haste-post-author"><i class="fa fw fa-user" aria-hidden="true"></i> <?php echo '<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . get_the_author() . '</a>'; ?></span>
							<?php endif; ?>

							<?php if( in_array( 'category', $meta ) ) : ?>
								<span class="haste-meta-info haste-post-category"><i class="fa fw fa-folder" aria-hidden="true"></i> <?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'haste-elementor-widgets' ) ); ?></span>
							<?php endif; ?>

							<?php if( in_array( 'comments', $meta ) ) : ?>
								<span class="haste-meta-info haste-post-comments"><i class="fa fw fa-comments" aria-hidden="true"></i> <?php echo comments_popup_link( __( 'Leave a comment', 'haste-elementor-widgets' ), __( '1 Comment', 'haste-elementor-widgets' ), __( '% Comments', 'haste-elementor-widgets' ) ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if( $this->get_settings( 'show_excerpt' ) == true ) : ?>
						<div class="haste-post-content">
							<?php
							if( strpos( get_the_content(), '<!--more-->' ) ) {
								the_content();
							}
							else {
								the_excerpt();
							}
							?>
						</div>
					<?php endif; ?>

					<?php if( $this->get_settings( 'show_read_more' ) == true ) : ?>
						<?php
						$this->add_render_attribute( 'wrapper', 'class', 'elementor-button-wrapper' );

						$this->add_render_attribute( 'read_more_button', 'class', 'elementor-button' );

						if ( ! empty( $settings['read_more_size'] ) ) {
							$this->add_render_attribute( 'read_more_button', 'class', 'elementor-size-' . $settings['read_more_size'] );
						}

						if ( $settings['read_more_hover_animation'] ) {
							$this->add_render_attribute( 'read_more_button', 'class', 'elementor-animation-' . $settings['read_more_hover_animation'] );
						}

						?>
						<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
							<a <?php echo $this->get_render_attribute_string( 'read_more_button' ); ?> href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php $this->render_text(); ?>
							</a>
						</div>
					<?php endif; ?>

				</div>
			</article >

		<?php }	?>
	</div>

	<?php
	/* Restore original Post Data */
	wp_reset_postdata();
} else {
	// no posts found
}

wp_reset_query();
?>

<?php
}
protected function content_template() {}

	/**
	* Render the button widget.
	*
	* @access protected
	*/
	protected function render_text() {
		$settings = $this->get_settings();
		$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );
		$this->add_render_attribute( 'read_more_icon-align', 'class', 'elementor-align-icon-' . $settings['read_more_icon_align'] );
		$this->add_render_attribute( 'read_more_icon-align', 'class', 'elementor-button-icon' );
		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['read_more_icon'] ) ) : ?>
				<span <?php echo $this->get_render_attribute_string( 'read_more_icon-align' ); ?>>
					<i class="<?php echo esc_attr( $settings['read_more_icon'] ); ?>"></i>
				</span>
			<?php endif; ?>
			<span class="elementor-button-text"><?php echo $settings['read_more_text']; ?></span>
		</span>
		<?php
	}
}
