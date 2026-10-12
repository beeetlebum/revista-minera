<?php
namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 *
 * ele-blog elementor Contact widget.
 *
 * @since 1.0
 */
class Ele_Blog_Style_Three extends Widget_Base {

    public function get_name() {
        return 'style_three';
    }

    public function get_title() {
        return esc_html__( 'Post Slider', 'ele-blog' );
    }

    public function get_icon() {
        return 'eleblog-icon eicon-post-list';
    }

    public function get_categories() {
        return ['ele-blog'];
    }

    protected function _register_controls() {


    // ----------------------------------------  content ------------------------------
     $this->start_controls_section(
            'ele_style_one_settings',
            [
                'label' => esc_html__( 'Generel Settings', 'ele-blog' ),
            ]
        );

        
        $this->add_control(
            'ppr', [
                'label'   => esc_html__( 'Amount of post to display', 'ele-blog' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 4
            ]
        );


        $this->end_controls_section(); // End Contact content

        $this->start_controls_section(
            'Post_filter_settings',
            [
                'label' => esc_html__( 'Post Filter', 'ele-blog' ),
            ]
        );

        $this->add_control(
            'select_cat', [
                'label'    => esc_html__( 'Select Category', 'ele-blog' ),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => ele_blog_post_category(),

            ]
        );


        $this->add_control(
            'exclude_cat', [
                'label'    => esc_html__( 'Exclude Category', 'ele-blog' ),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => ele_blog_post_category(),
            ]
        );

        $this->add_control(
            'select_tag', [
                'label'    => esc_html__( 'Select tag', 'ele-blog' ),
                'type'     => Controls_Manager::SELECT2,
                'multiple' => true,
                'options'  => ele_blog_post_tag(),
            ]
        );

        $this->add_control(
            'orderby', [
                'label'   => esc_html__( 'Order by', 'ele-blog' ),
                'type'    => Controls_Manager::SELECT2,
                'options' => array(
                    'author' => esc_html__( 'Author', 'ele-blog' ),
                    'title'  => esc_html__( 'Title', 'ele-blog' ),
                    'date'   => esc_html__( 'Date', 'ele-blog' ),
                    'rand'   => esc_html__( 'Random', 'ele-blog' ),
                ),
                'default' => 'date'

            ]
        );

        $this->add_control(
            'order', [
                'label'   => esc_html__( 'Order', 'ele-blog' ),
                'type'    => Controls_Manager::SELECT2,
                'options' => array(
                    'desc' => esc_html__( 'DESC', 'ele-blog' ),
                    'asc'  => esc_html__( 'ASC', 'ele-blog' ),
                ),
                'default' => 'desc'

            ]
        );

  
        $this->end_controls_section(); // End Contact content

        //title settings
        $this->start_controls_section(
            'tittle_settings',
            [
                'label' => esc_html__( 'Title Settings', 'ele-blog' ),
            ]
        );

         $this->add_control(
            'dtitle', [
                'label'   => esc_html__( 'Display', 'ele-blog' ),
                'type'    => Controls_Manager::SELECT2,
                'options' => array(
                    'full' => esc_html__( 'Full Title', 'ele-blog' ),
                    'excerpt'  => esc_html__( 'Excerpt', 'ele-blog' ),
                    'hide'  => esc_html__( 'Not Display', 'ele-blog' ),
                ),
                'default' => 'full'

            ]
        );

        $this->add_control(
            'title_excerpt_length', [
                'label'   => esc_html__( 'Title Excerpt Length', 'ele-blog' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 3,
                'condition' =>[
                    'dtitle'=>'excerpt'
                ]
            ]
        );

        $this->add_control(
            'titltag', [
                'label'   => esc_html__( 'Title Tag', 'ele-blog' ),
                'type'    => Controls_Manager::SELECT,
                'condition'=>[
                    'dtitle!' => 'hide'
                ],
                'options' => array(
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                ),
                'default' => 'h2'

            ]
        );

        $this->end_controls_section(); // End title

        //meta settings
        $this->start_controls_section(
            'meta_settings',
            [
                'label' => esc_html__( 'Meta Settings', 'ele-blog' ),
            ]
        );

        $this->add_control(
            'enable_meta',
            [
                'label' => esc_html__( 'Meta', 'ele-blog' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ele-blog' ),
                'label_off' => esc_html__( 'no', 'ele-blog' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );      

        $this->add_control(
            'metaposition', [
                'label'   => esc_html__( 'Meta Position', 'ele-blog' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'before' => esc_html__( 'Before Title', 'ele-blog' ),
                    'after'  => esc_html__( 'After Title', 'ele-blog' ),
                ),
                'default' => 'before'

            ]
        );


        $this->add_control(
            'enable_author',
            [
                'label' => esc_html__( 'Display Author Name', 'ele-blog' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ele-blog' ),
                'label_off' => esc_html__( 'no', 'ele-blog' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition'=>[
                    'enable_meta' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'enable_date',
            [
                'label' => esc_html__( 'Display Date', 'ele-blog' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ele-blog' ),
                'label_off' => esc_html__( 'no', 'ele-blog' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition'=>[
                    'enable_meta' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'enable_comment',
            [
                'label' => esc_html__( 'Display Comment Number', 'ele-blog' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ele-blog' ),
                'label_off' => esc_html__( 'no', 'ele-blog' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition'=>[
                    'enable_meta' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'enable_category',
            [
                'label' => esc_html__( 'Display Category', 'ele-blog' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ele-blog' ),
                'label_off' => esc_html__( 'no', 'ele-blog' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition'=>[
                    'enable_meta' => 'yes'
                ]
            ]
        );



        $this->end_controls_section(); // End title


        //title style
        $this->start_controls_section(
            'title_style', [
                'label' => esc_html__( 'Title Style', 'ele-blog' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'ele-blog' ),
                'type' => Controls_Manager::COLOR,
                 'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eblog-single-inner .inner-title a' => 'color: {{VALUE}}',
                ],
            ]
        );

       $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__( 'Title Hover Color', 'ele-blog' ),
                'type' => Controls_Manager::COLOR,
                 'default' => '#1368ab',
                'selectors' => [
                    '{{WRAPPER}} .eblog-single-inner .inner-title a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        //title typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'label'          => esc_html__( 'Title Typography', 'ele-blog' ),
                'selector'       => '{{WRAPPER}} .eblog-single-inner .inner-title a',
                'fields_options' => [
                    // first mimic the click on Typography edit icon
                    'typography'  => [ 'default' => 'yes' ],
                    // then redifine the Elementor defaults
                    'font_size'   => [ 'default' => [ 'size' => 24 ] ],
                    'font_weight' => [ 'default' => 700 ],
                    'line_height' => [ 'default' => [ 'size' => 29 ] ],
                ],
            ]
        );

     $this->end_controls_section();

    //meta style
    $this->start_controls_section(
        'meta_style', [
            'label' => esc_html__( 'Meta Style', 'ele-blog' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_control(
        'meta_color',
        [
            'label' => esc_html__( 'Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .eblog-meta-inner li, .eblog-meta-inner li a ' => 'color: {{VALUE}}',
            ],
        ]
    );

    $this->add_control(
        'meta_icon_color',
        [
            'label' => esc_html__( 'Icon Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .eblog-meta-inner li svg' => 'color: {{VALUE}}',
            ],
        ]
    );


    //meta typography
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'           => 'meta_typography',
            'label'          => esc_html__( 'Meta Typography', 'ele-blog' ),
            'selector'       => '{{WRAPPER}} .eblog-meta-inner li',
            'fields_options' => [
                // first mimic the click on Typography edit icon
                'typography'  => [ 'default' => 'yes' ],
                // then redifine the Elementor defaults
                'font_size'   => [ 'default' => [ 'size' => 13 ] ],
                'font_weight' => [ 'default' => 400 ],
                'line_height' => [ 'default' => [ 'size' => 21 ] ],
            ],
        ]
    );

     $this->end_controls_section();

    //slider button color
    $this->start_controls_section(
        'slider_button_style', [
            'label' => esc_html__( 'Slider Button Style', 'ele-blog' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_control(
        'button_icon_color',
        [
            'label' => esc_html__( 'Icon Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#1368ab',
            'selectors' => [
                '{{WRAPPER}} .slider-control-base button' => 'color: {{VALUE}};border-color:{{VALUE}}',
            ],
        ]
    );  

    $this->add_control(
        'button_hover_color',
        [
            'label' => esc_html__( 'Hover Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#1368ab',
            'selectors' => [
                '{{WRAPPER}} .slider-control-base button:hover' => 'background-color: {{VALUE}};',
            ],
        ]
    );


    $this->end_controls_section();

    // more
    $this->start_controls_section(
        'morefeautes_settings',
        [
            'label' => esc_html__( 'Go Pro ', 'ele-blog' ),
        ]
    );

    $this->add_control(
        'important_note',
        [
            'label' => '',
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => '
            <a target="_blank" href="https://1.envato.market/ele-blog">Go Pro</a>
            ',
        ]
    );

    $this->end_controls_section(); // More 


        
    }

    protected function render() {

    ?>

    <div class="text-center">
        <h2>This Widget Only Available In <a href="https://1.envato.market/ele-blog" target="_blank" style="text-decoration:underline;color:#222">Pro Version</a></h2>
    </div>

    <?php    

    }
    

}

plugin::instance()->widgets_manager->register_widget_type(new Ele_Blog_Style_Three());
