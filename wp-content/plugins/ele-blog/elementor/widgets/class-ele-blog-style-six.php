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
class Ele_Blog_Style_Six extends Widget_Base {

    public function get_name() {
        return 'style_six';
    }

    public function get_title() {
        return esc_html__( 'Card Style', 'ele-blog' );
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

        $this->end_controls_section(); // End generel settings

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


        // pagination
        $this->start_controls_section(
            'pagination_settings',
            [
                'label' => esc_html__( 'Pagination Settings', 'ele-blog' ),
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

        $this->end_controls_section(); // End pagination 

                 // more
        $this->start_controls_section(
            'morefeautes_settings',
            [
                'label' => esc_html__( 'More ', 'ele-blog' ),
            ]
        );

        $this->add_control(
            'important_note',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<br/>For More Style And Features Lke Magazine, Ajax pagination, Video grid, Video Popup, trending post and more 13+ addons <br/>
                <br/><a target="_blank" href="https://1.envato.market/ele-blog">Go Pro</a>
                <br/><br/>
                 <h3>Please give us a <a target="_blank" href="https://wordpress.org/support/plugin/ele-blog/reviews/#new-post">Rating</a> To improve this plugin. If you need any help you can contact us at <a target="_blank" href="https://solverwp.com/">SolverWp</a></h3>',
                'content_classes' => 'your-class',
            ]
        );

        $this->end_controls_section(); // More 

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

    <section class="eblog-area style-10" >
        <div class="eblog-container">
            <div class="eblog-row">
                <?php 
                if ( $posts_query->have_posts() ):
                    while ( $posts_query->have_posts() ): $posts_query->the_post(); ?>
                  <div class="eblog-col-lg-<?php echo esc_attr( $settings[ 'layout' ] ); ?> eblog-col-sm-6">
                    <div class="eblog-single-inner style-10">
                        <div class="icon-img">
                            <?php the_post_thumbnail(); ?>
                        </div> 
                        <div class="content-box">

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

                        <?php endif; // end title  ?>

                            <div class="e-book-author-inner">
                               <?php echo get_avatar( get_the_author_meta( 'ID' ) , 45 ); ?>
                                <div class="author-details">
                                    <p><?php the_author(); ?></p>
                                    <span><?php the_time( get_option( 'date_format' ) ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
             </div>
            <?php if( 'yes' == $settings[ 'enable_pagination' ] ) : ?>
                 <div class="eblog-row eleblog-norma-pagi">
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
                </div>
            <?php endif; ?>
        </div>
    </section> 
       
    <?php    

    }
    

}

plugin::instance()->widgets_manager->register_widget_type(new Ele_Blog_Style_Six());
