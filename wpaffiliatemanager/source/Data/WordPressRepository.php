<?php
/**
 * @author John Hargrove
 * 
 * Date: May 23, 2010
 * Time: 7:56:51 PM
 *
 * @TODO most of this can probably go away
 */

class WPAM_Data_WordPressRepository
{
	private $db;
	public function __construct(wpdb $db)
	{
		$this->db = $db;
	}

	public function getAllImageAttachments()
	{
		$query = "
			SELECT *
			FROM {$this->db->posts}
			where
				post_type = 'attachment'
				and post_mime_type like 'image/%'";
		
		return $this->db->get_results($query);
	}

	public function postExists($postName)
	{
		$query = $this->db->prepare(
			"SELECT COUNT(*)
			FROM `{$this->db->prefix}posts`
			WHERE
				post_name=%s", $postName
		);
		return $this->db->get_var($query) > 0;
	}

	public function getPostId($postName)
	{
		$query = $this->db->prepare(
			"SELECT ID
			FROM `{$this->db->prefix}posts`
			WHERE
				post_name=%s", $postName
		);
		return $this->db->get_var($query);
	}
}
