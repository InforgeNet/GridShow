<?php

namespace Inforge\GridShow\Entity;

use XF\Mvc\Entity\Structure;

class Tile extends \XF\Mvc\Entity\Entity
{
	public static function getStructure(Structure $structure)
	{
		$structure->table = 'xf_if_gs_tiles';
		$structure->shortName = 'Inforge\GridShow:Tile';
		$structure->primaryKey = 'tile_id';
		$structure->columns = [
			'tile_id' => [
				'type' => self::UINT,
				'autoIncrement' => true
			],
			'title' => [
				'type' => self::STR,
				'maxLength' => 150,
				'required' => 'please_enter_valid_title'
			],
			'category' => [
				'type' => self::STR,
				'maxLength' => 25,
				'default' => ''
			],
			'link' => [
				'type' => self::STR,
				'maxLength' => 200,
				'required' => 'please_enter_valid_url'
			],
			'image_url' => [
				'type' => self::STR,
				'maxLength' => 200,
				'required' => 'please_enter_valid_image'
			],
			'display_order' => [
				'type' => self::UINT,
				'default' => 0
			],
			'active' => [
				'type' => self::BOOL,
				'default' => true
			],
			'font_size' => [
				'type' => self::STR,
				'maxLength' => 10,
				'required' => false
			]
		];

		$structure->getters = [];
		$structure->relations = [];

		return $structure;
	}

	protected function verifyLink($link)
	{
		if ($this->isUpdate() && $link === $this->getExistingValue('link'))
			return true;

		$urlValidator = $this->app()->validator('Url');
		if ($urlValidator->isValid($link, $errorKey))
			return true;

		if ($errorKey == 'disallowed_scheme')
			$this->error(\XF::phrase('if_gs_url_contains_disallowed_scheme'));
		else
			$this->error(\XF::phrase('please_enter_valid_url'));
		return false;
	}

	protected function verifyFontSize($fontSize)
	{
		if ($fontSize === null || $fontSize === '')
			return true;
		if (preg_match(
			'/^[ \t]*[0-9]*\.*[0-9]+(cm|mm|in|px|pt|pc|em|ex|ch|rem|vh|vw|vim|vmax|\%)[ \t]*$/',
			$fontSize) !== 1) {
			$this->error(\XF::phrase('if_gs_please_enter_valid_font_size_with_unit'));
			return false;
		}
		return true;
	}

	protected function getTileRepo()
	{
		return $this->repository('Inforge\GridShow:Tile');
	}
}
