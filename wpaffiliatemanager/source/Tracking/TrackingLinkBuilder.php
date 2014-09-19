<?php
/**
 * @author John Hargrove
 * 
 * Date: Jun 27, 2010
 * Time: 5:09:21 PM
 */

class WPAM_Tracking_TrackingLinkBuilder
{
	private $affiliate;
	private $creative;

	public function __construct(WPAM_Data_Models_AffiliateModel $affiliate, WPAM_Data_Models_CreativeModel $creative) {
		$this->affiliate = $affiliate;
		$this->creative = $creative;
	}

	public function getTrackingKey() {
		$trackingKey = new WPAM_Tracking_TrackingKey();

		$trackingKey->setAffiliateRefKey($this->affiliate->uniqueRefKey);
		$trackingKey->setCreativeId($this->creative->creativeId);

		return $trackingKey;
	}

	public function getUrl() {
		return add_query_arg( array( WPAM_PluginConfig::$RefKey => $this->getTrackingKey()->pack() ),
							  site_url( trim( $this->creative->slug ) ) );
	}

	public function getHtmlSnippet() {
		switch ($this->creative->type) {
			case 'image':
				$html = "<a href=\"" . $this->getUrl() . "\">";
				$html .= "<img src=\"" . wp_get_attachment_url($this->creative->imagePostId) . "\" style=\"border: 0;\" title=\"{$this->creative->altText}\"/>";
				$html .= "</a>";
				return $html;
			case 'text':
				$html = "<a href=\"" . $this->getUrl() . "\" title=\"{$this->creative->altText}\">";
				$html .= $this->creative->linkText;
				$html .= "</a>";
				return $html;

			default:
				return NULL;
		}
	}
}
