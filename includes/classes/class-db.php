<?php

namespace Sr_User_Feedback;

/**
 * The db class
 */
class Db {

	/**
	 * Initialize DB table.
	 */
	public static function init_db() {
		global $wpdb;

		$feedback_table_name = $wpdb->prefix . 'feedback_submissions';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( $wpdb->prepare( 'show tables like %s', $feedback_table_name ) ) !== $feedback_table_name ) {
			$sql = sprintf(
				'CREATE TABLE `%s` (
				`id`  bigint(20)   NOT NULL auto_increment,
				`firstname`  tinytext   NOT NULL,
				`lastname`  tinytext   NOT NULL,
				`email`  varchar(100)   NOT NULL,
				`subject`  tinytext   NOT NULL,
				`message`  mediumtext   NOT NULL,
				`created_at` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
				PRIMARY KEY (`id`),
				KEY email (email)
			) %s;',
				$feedback_table_name,
				$charset_collate
			);

			require_once ABSPATH . '/wp-admin/includes/upgrade.php';

			dbDelta( $sql );
		}
	}

	/**
	 * Insert into DB table.
	 *
	 * @param array $post_data Submitted form data.
	 * 
	 * @return boolean|int
	 */
	public static function insert( $post_data ) {
		global $wpdb;

		$post_data = wp_unslash( $post_data );

		if ( isset( $post_data['action'] ) && 'submit_feedback' === $post_data['action'] ) {
			$data = array();
			unset( $post_data['action'] );
			unset( $post_data['security'] );

			$feedback_table_name = $wpdb->prefix . 'feedback_submissions';

			//input sanitization
			foreach ( $post_data as $key => $value ) {
				if ( $key == 'email' ) {
					$data[$key] = sanitize_email( $value ); 
				} elseif ( $key == 'message' ) {
					$data[$key] = sanitize_textarea_field( $value ); 
				} else {
					$data[$key] = sanitize_text_field( $value ); 
				}
			}

			$wpdb->insert( $feedback_table_name, $data );
			return $wpdb->insert_id;
		}
		return false;
	}

	/**
	 * Get items from db table
	 *
	 * @param array $request Db filter data.
	 * 
	 * @return array
	 */
	public static function get_items( $request ) {
		global $wpdb;

		$request = wp_unslash( $request );

		$feedback_table_name = $wpdb->prefix . 'feedback_submissions';

		$current_page = $request['current_page'];
		$per_page = $request['per_page'];
		$order = $request['order'];

		$sql = "SELECT * FROM $feedback_table_name";
		
		$search = isset( $request['search'] ) ? esc_sql( $request['search'] ) : '';
		$search_term = '';

		if ( ! empty( $search ) ) {
			$search_term = ' WHERE (firstname LIKE "%' . $search  . '%"' ;
			$search_term .= ' OR lastname LIKE "%' . $search  . '%"' ;
			$search_term .= ' OR subject LIKE "%' . $search  . '%"' ;
			$search_term .= ' OR email LIKE "%' . $search  . '%")' ;
		}

		$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $feedback_table_name $search_term" );

		$sql .= $search_term;
		$sql .= ' ORDER BY created_at';
		$sql .= " $order";
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $current_page - 1 ) * $per_page;

		$numbr_of_pages = ceil( $total_items / $per_page );
		
		return array(
			'feedbacks'       => $wpdb->get_results( $sql, OBJECT ),
			'number_of_pages' => $numbr_of_pages,
			'current_page'    => $current_page,
			'next_page'       => ( $current_page < $numbr_of_pages ) ? $current_page + 1 : false,
			'prev_page'       => ( $current_page > 1 && $current_page <= $numbr_of_pages ) ? $current_page - 1 : false,
			'message'         => $total_items ? '' : __( 'Sorry! no feedback found.', 'user-feedback' )
		);
	}

	/**
	 * Delete item from db table
	 *
	 * @param array $id Db item id.
	 * 
	 * @return boolean|int
	 */
	public static function delete_item( $id ) {
		global $wpdb;
		$feedback_table_name = $wpdb->prefix . 'feedback_submissions';
		return $wpdb->delete(
			$feedback_table_name,
			array( 'id' => $id ),
			array( '%d' ),
		);
	}

	/**
	 * Get item from db table
	 *
	 * @param array $id Db item id.
	 * @return object
	 */
	public static function get_item( $id ) {
		global $wpdb;
		$feedback_table_name = $wpdb->prefix . 'feedback_submissions';
		return $wpdb->get_row( "SELECT * FROM $feedback_table_name WHERE `id` = $id", OBJECT );
	}
}

