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
class Ele_Blog_Style_One extends Widget_Base {

    public function get_name() {
        return 'style_one';
    }

    public function get_title() {
        return esc_html__( 'Style One', 'ele-blog' );
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['ele-blog'];
    }

    protected function _register_controls() {


    // generel settings
     $this->start_controls_section(
            'ele_style_one_settings',
            [
                'label' => esc_html__( 'Generel Settings', 'ele-blog' ),
            ]
        );

     $this->add_control(
            'style', [
                'label'   => esc_html__( 'Select Style', 'ele-blog' ),
                'type'    => Controls_Manager::SELECT2,
                'options' => array(
                    'style-1' => esc_html__( 'Style 1', 'ele-blog' ),
                    'style-2'  => esc_html__( 'Style 2', 'ele-blog' ),
                    'style-3'  => esc_html__( 'Style 3', 'ele-blog' ),
                    'style-4'  => esc_html__( 'Style 4', 'ele-blog' ),
                    'style-5'  => esc_html__( 'Style 5', 'ele-blog' ),
                    'style-6'  => esc_html__( 'Style 6', 'ele-blog' ),
                ),
                'default' => 'style-1'

            ]
        );
        
        $this->add_control(
            'ppr', [
                'label'   => esc_html__( 'Amount of post to display', 'ele-blog' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 3
            ]
        );

        $this->add_control(
            'layout', [
                'label'   => esc_html__( 'layout', 'ele-blog' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    '2' => '6',
                    '3' => '4',
                    '4' => '3',
                    '6' => '2',
                ),
                'default' => 4

            ]
        );


        $this->end_controls_section(); // End  generel settings

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
            'title_settings',
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
                'default' => 'excerpt'

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
            'enable_comment',
            [
                'label' => esc_html__( 'Display Comment Number', 'ele-blog' ),
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
            'enable_category',
            [
                'label' => esc_html__( 'Display Category', 'ele-blog' ),
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



        $this->end_controls_section(); // End title

        // content settings

        $this->start_controls_section(
            'conent_settings',
            [
                'label' => esc_html__( 'Content Settings', 'ele-blog' ),
            ]
        );

         $this->add_control(
            'dcontent', [
                'label'   => esc_html__( 'Display', 'ele-blog' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'full' => esc_html__( 'Full Content', 'ele-blog' ),
                    'excerpt'  => esc_html__( 'Excerpt', 'ele-blog' ),
                    'hide'  => esc_html__( 'Hide', 'ele-blog' ),
                ),
                'default' => 'excerpt'

            ]
        );


        $this->add_control(
            'excerpt_length', [
                'label'   => esc_html__( 'Excerpt Length', 'ele-blog' ),
                'type'    => Controls_Manager::TEXT,
                'default' => 15,
                'condition' =>[
                    'dcontent'=>'excerpt'
                ]
            ]
        );

        $this->end_controls_section(); // End  content settings

        // read more button
        $this->start_controls_section(
            'readmore_settings',
            [
                'label' => esc_html__( 'Read More Settings', 'ele-blog' ),
            ]
        );

        $this->add_control(
            'enable_readmore',
            [
                'label' => esc_html__( 'Display Read More', 'ele-blog' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ele-blog' ),
                'label_off' => esc_html__( 'no', 'ele-blog' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'readmorestyle', [
                'label'   => esc_html__( 'Read More Style', 'ele-blog' ),
                'condition' =>[
                    'enable_readmore' => 'yes'
                ],
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'readmore-text' => esc_html__( 'Normal Text', 'ele-blog' ),
                    'readmore-btn'  => esc_html__( 'Button', 'ele-blog' ),
                ),
                'default' => 'readmore-text'

            ]
        );

        $this->add_control(
            'btntext', [
                'label'   => esc_html__( 'Button Text', 'ele-blog' ),
                'condition' =>[
                    'enable_readmore' => 'yes'
                ],
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Read More','ele-blog' ),
            ]
        );

          $this->add_control(
            'enable_btn_icon',
            [
                'label' => esc_html__( 'Display Button Icon', 'ele-blog' ),
                'condition' =>[
                      'enable_readmore' => 'yes',                    
                ],
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ele-blog' ),
                'label_off' => esc_html__( 'no', 'ele-blog' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__( 'Button Icon', 'ele-blog' ),
                'condition' =>[
                      'enable_readmore' => 'yes',                    
                      'enable_btn_icon' => 'yes',                    
                ],
                'type' => \Elementor\Controls_Manager::ICON,
                'default' => 'fa fa-angle-right',
            ]
        );


        $this->end_controls_section(); // End readmore 

        $this->start_controls_section(
            'Pagination',
            [
                'label' => esc_html__( 'Pagination', 'ele-blog' ),
            ]
        );

         $this->add_control(
            'enable_pagination',
            [
                'label' => esc_html__( 'Enable Pagination', 'ele-blog' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'ele-blog' ),
                'label_off' => esc_html__( 'no', 'ele-blog' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'pagination_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => 'Ajax Pagination ( Without Loading Page ) Available On <a target="_blank" href="https://1.envato.market/ele-blog">Go Pro</a><br/>
                <a target="_blank" href="https://solverwp.com/demo/wp/ele-blog/ajax-pagination/">See Demo</a>',
                'content_classes' => 'your-class',
            ]
        );

        $this->end_controls_section(); // End readmore 

        // more
        $this->start_controls_section(
            'morefeautes_settings',
            [
                'label' => esc_html__( 'More Style', 'ele-blog' ),
            ]
        );

        $this->add_control(
            'important_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<br/>For More Style And Features Lke Magazine, Ajax pagination and more 13+ addons  <br/>
                <br/><a target="_blank" href="https://1.envato.market/ele-blog">Go Pro</a>
                <br/><br/>
                 <h3>Please give us a <a target="_blank" href="https://wordpress.org/support/plugin/ele-blog/reviews/#new-post">Rating</a> To improve this plugin. If you need any help you can contact us at <a target="_blank" href="https://solverwp.com/">SolverWp</a></h3>',
                'content_classes' => 'your-class',
            ]
        );

        $this->end_controls_section(); // End readmore 


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
                 'default' => '#222',
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
             'default' => '#484848',
            'selectors' => [
                '{{WRAPPER}} .eblog-meta-inner li a,.eblog-meta-inner li ' => 'color: {{VALUE}}',
            ],
        ]
    );

    $this->add_control(
        'meta_icon_color',
        [
            'label' => esc_html__( 'Icon Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#1368ab',
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

    //content style
    $this->start_controls_section(
        'content_style', [
            'label' => esc_html__( 'Content Style', 'ele-blog' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ]
    );

    $this->add_control(
        'content_color',
        [
            'label' => esc_html__( 'Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#484848',
            'selectors' => [
                '{{WRAPPER}} p' => 'color: {{VALUE}}',
            ],
        ]
    );


    //content typography
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'           => 'content_typography',
            'label'          => esc_html__( 'Content Typography', 'ele-blog' ),
            'selector'       => '{{WRAPPER}} p',
            'fields_options' => [
                // first mimic the click on Typography edit icon
                'typography'  => [ 'default' => 'yes' ],
                // then redifine the Elementor defaults
                'font_size'   => [ 'default' => [ 'size' => 15 ] ],
                'font_weight' => [ 'default' => 400 ],
                'line_height' => [ 'default' => [ 'size' => 24 ] ],
            ],
        ]
    );

     $this->end_controls_section();

     //read more style
    $this->start_controls_section(
        'read_style', [
            'label' => esc_html__( 'Read More Style', 'ele-blog' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition'=>[
                'enable_readmore'=>'yes'
            ]
        ]
    );

    $this->add_control(
        'read_more_btn_color',
        [
            'label' => esc_html__( 'Button Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#484848',
            'selectors' => [
                '{{WRAPPER}} .eblog-single-inner .readmore-btn' => 'color: {{VALUE}}',
            ],
            'condition'=>[
                'readmorestyle'=>'readmore-btn',
            ],
        ]
    );  

    $this->add_control(
        'read_more_btn_hover_color',
        [
            'label' => esc_html__( 'Button Hover Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .eblog-single-inner .readmore-btn:hover' => 'color: {{VALUE}}',
            ],
            'condition'=>[
                'readmorestyle'=>'readmore-btn',
            ],
        ]
    );

    $this->add_control(
        'read_more_btn_bg_color',
        [
            'label' => esc_html__( 'Button Background Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => 'transparent',
            'selectors' => [
                '{{WRAPPER}} .eblog-single-inner .readmore-btn' => 'background-color: {{VALUE}}',
            ],
            'condition'=>[
                'readmorestyle'=>'readmore-btn',
            ],
        ]
    );


    $this->add_control(
        'read_more_btn_hover_bg_color',
        [
            'label' => esc_html__( 'Button Hover Background Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#1368ab',
            'selectors' => [
                '{{WRAPPER}} .eblog-single-inner .readmore-btn:hover' => 'background-color: {{VALUE}};border-color:{{VALUE}}',
            ],
            'condition'=>[
                'readmorestyle'=>'readmore-btn',
            ],
        ]
    );


     $this->add_control(
        'read_more_text_color',
        [
            'label' => esc_html__( 'Color', 'ele-blog' ),
            'type' => Controls_Manager::COLOR,
             'default' => '#484848',
            'selectors' => [
                '{{WRAPPER}} .readmore-text' => 'color: {{VALUE}}',
            ],
            'condition'=>[
                'readmorestyle'=>'readmore-text',
            ],
        ]
    ); 

   //read more typography
    $this->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name'           => 'read_more_text_typography',
            'label'          => esc_html__( 'Typography', 'ele-blog' ),
            'selector'       => '{{WRAPPER}} .eblog-single-inner .readmore-text',
            'fields_options' => [
                // first mimic the click on Typography edit icon
                'typography'  => [ 'default' => 'yes' ],
                // then redifine the Elementor defaults
                'font_size'   => [ 'default' => [ 'size' => 15 ] ],
                'font_weight' => [ 'default' => 400 ],
                'line_height' => [ 'default' => [ 'size' => 24 ] ],
            ],
            'condition'=>[
                'readmorestyle'=>'readmore-text',
            ],
        ]
    ); 

    $this->end_controls_section();
        
    }

    protected function render() {

    $settings = $this->get_settings();

    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

    $args  = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => $settings['ppr'],
        'paged'=> $paged,
    );
    $args['orderby'] = $settings['orderby'];
    $args['order']   = $settings['order'];
    if ( ! empty( $settings['exclude_cat'] ) ) {
        $args['category__not_in'] = $settings['exclude_cat'];
    }


    if ( ! empty( $settings['select_cat'] ) ) {
        $catgory             = implode( ', ', $settings['select_cat'] );
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'id',
            'terms'    => array_values( $settings['select_cat'] )
        );
    }

    if ( ! empty( $settings['select_tag'] ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'post_tag',
            'field'    => 'id',
            'terms'    => array_values( $settings['select_tag'] )
        );
    }

    $posts_query = new \WP_Query( $args );


    ?>

       <section class="eblog-area <?php echo esc_attr(  $settings[ 'style' ] ); ?>">
            <div class="eblog-container">
                <div class="eblog-row">
                <?php 

                if ( $posts_query->have_posts() ):
                    while ( $posts_query->have_posts() ): $posts_query->the_post();
                ?>
                    <div class="eblog-col-lg-<?php echo esc_attr( $settings[ 'layout' ] ); ?>           eblog-col-sm-6">
                            <div class="eblog-single-inner <?php echo esc_attr(  $settings[ 'style' ] ); ?>">

                            <div class="icon-img">
                               <?php the_post_thumbnail(); ?>
                            </div> 
                            <div class="content-box">

                            <?php 
                            //enable meta
                             if( 'yes' == $settings[ 'enable_meta' ] && 'before' == $settings[ 'metaposition' ] ):
                            ?>
                                <ul class="eblog-meta-inner">
                                    <?php if( 'yes' == $settings[ 'enable_author' ] ) : ?>
                                        <li><i class="fa fa-user"></i>
                                            <?php the_author(); ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php if( 'yes' == $settings[ 'enable_comment' ]  ) : ?>
                                        <li><i class="fa fa-comment-alt"></i>
                                            <?php comments_popup_link(); ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php if( 'yes' == $settings[ 'enable_category' ] ) : ?>
                                        <li><i class="fa fa-tag"></i><?php the_category(', '); ?></li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>

                            <?php 

                                //check if not hide title
                                if( 'hide' != $settings[ 'dtitle' ] ) :

                                ?>
                                <<?php echo esc_attr( $settings[ 'titltag' ] ); ?> class="inner-title">

                                <a href="<?php the_permalink(); ?>">

                                    <?php
                                     if( 'full' == $settings[ 'dtitle' ] ) :

                                      the_title();

                                      else :
                                        echo esc_html( wp_trim_words( get_the_title(), $settings[ 'title_excerpt_length' ], '' ) );
                                     endif;
                                     ?>
                                        
                                </a>

                                </<?php echo esc_attr( $settings[ 'titltag' ] ); ?>>

                                <?php endif; // end title ?>

                            <?php 
                            
                            //enable meta
                             if( 'yes' == $settings[ 'enable_meta' ] && 'after' == $settings[ 'metaposition' ] ):
                            ?>
                                <ul class="eblog-meta-inner">
                                    <?php if( 'yes' == $settings[ 'enable_author' ] ) : ?>
                                        <li><i class="fa fa-user"></i>
                                            <?php the_author(); ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php if( 'yes' == $settings[ 'enable_comment' ]  ) : ?>
                                        <li><i class="fa fa-comment-alt"></i>
                                            <?php comments_popup_link(); ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php if( 'yes' == $settings[ 'enable_category' ] ) : ?>
                                        <li><i class="fa fa-tag"></i><?php the_category(', '); ?></li>
                                    <?php endif; ?>
                                </ul>
                              <?php endif; 

                                // check if not hide content
                                if( 'hide' != $settings[ 'dcontent' ] ) :

                                  //check if excerpt enable
                                  if( 'excerpt' == $settings[ 'dcontent' ] ) : ?>
                                    <p><?php echo wp_kses_post( wp_trim_words( get_the_content(), $settings[ 'excerpt_length'] , '' ) ); ?></p>
                                  <?php else : 
                                    the_content();
                                   endif;
                                 endif;

                                //check if read more enable
                                if( 'yes' == $settings[ 'enable_readmore' ] ) :
                                ?>

                                <a class="<?php echo esc_attr( $settings[ 'readmorestyle' ] ); ?>" href="<?php the_permalink( ); ?>">

                                        <?php echo esc_html( $settings[ 'btntext' ] ); ?>
                                        <?php if( 'yes' == $settings[ 'enable_btn_icon' ] ) : ?>
                                            <i class="<?php echo esc_attr( $settings[ 'icon' ] ); ?>"></i>
                                        <?php endif; ?>
                                    </a>
                                <?php endif; ?>
                                </div>
                            </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); endif; ?>
                </div>
            </div>
            <div class="eblog-row eleblog-norma-pagi">
            <?php if( 'yes' == $settings[ 'enable_pagination' ] ) : ?>
                <div class="eblog-col-lg-12 text-center">
                    <?php

                    $add_args = [];

                    $big = 999999999; 
                    $pages = paginate_links( array( 
                            'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
                            'format'       => '?paged=%#%',
                            'current'      => max( 1, get_query_var( 'paged' ) ),
                            'total'        => $posts_query->max_num_pages,
                            'type'         => 'array',
                            'show_all'     => false,
                            'end_size'     => 3,
                            'mid_size'     => 1,
                            'prev_next'    => false,
                            'next_text'    => false,
                            'add_args'     => $add_args,
                            'add_fragment' => ''
                         )
                    );

                    if ( is_array( $pages ) ) {
                        $pagination = '<div class="eleblog-pagination"><ul class="pagination pagination-2">';

                        foreach ( $pages as $page ) {
                           $pagination .= '<li class="page-item '.(strpos($page, 'current') !== false ? 'active' : '').'"> ' . str_replace( 'page-numbers', 'page-link', $page ) . '</li>';
                        }

                        $pagination .= '</ul></div>';

                        echo wp_kses_post( $pagination );

                    }

                    wp_reset_postdata();
                    ?>

                </div>
                <?php endif; ?>
        </div>
        </section>  

        <?php    

    }
    

}

plugin::instance()->widgets_manager->register_widget_type(new Ele_Blog_Style_One());
