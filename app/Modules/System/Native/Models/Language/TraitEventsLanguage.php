<?php
/**
 * Created by PhpStorm.
 * User: Webhouse
 * Date: 12/30/2018
 * Time: 10:26 AM
 */

namespace Modules\System\Native\Models\Language;


use Lib\Mvc\Helper\CmsCache;
use Modules\System\Native\Models\Translate\ModelTranslate;
use Phalcon\Di;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\Query\Builder;

trait TraitEventsLanguage
{
    public function beforeCreate()
    {
       // $this->setOnlyOnePrimary();
//        if(!$this->position)
//            $this->position = $this->getUpperPosition() + 1;
//
//        if(!$this->is_primary)
//            $this->is_primary = 0;
//
//        if($this->position)
//        {
//            $langPlusPosition = self::find([
//                'conditions' => 'position >= '. $this->position
//            ]);
//
//            foreach($langPlusPosition as $lang)
//            {
//                $lang->position = $lang->position + 1;
//                $lang->update();
//            }
//        }
    }

    public function beforeUpdate()
    {
        //        $cache = $this->getDI()->get('cache');
        //        $cache->delete(self::cacheKey());
    }

    public function beforeDelete()
    {
        if($this->is_primary)
        {
            return false;
        }
    }

    public function beforeSave()
    {
        $this->updateTranslate();

         $this->setOnlyOnePrimary();
    }

    public function afterUpdate()
    {
        //        $cache = $this->getDI()->get('cache');
        //        $cache->delete(self::cacheKey());
    }

    public function afterSave()
    {
        CmsCache::getInstance()->save('languages', $this->buildCmsLanguagesCache());
        CmsCache::getInstance()->save('translates', ModelTranslate::buildCmsTranslatesCache());
        $this->updateTranslate();

    }

    public function afterDelete()
    {
        CmsCache::getInstance()->save('languages', $this->buildCmsLanguagesCache());
        CmsCache::getInstance()->save('translates', ModelTranslate::buildCmsTranslatesCache());
    }

    public function afterValidation()
    {
//        if (!$this->position) {
//            $this->position = $this->getUpperPosition();
//        }

    }

    public function afterValidationOnCreate()
{
//        $this->position = $this->getUpperPosition() + 1;
}

    public function updateTranslate()
    {
        $language = ModelLanguage::find( );

        $phrases = ModelTranslate::find(
            [
                'columns' => 'phrase,language'
            ]
        );
       // dump($phrases->toArray());
        foreach ($phrases as $phrase)
        {
            if ($phrase['phrase'][$this->getIso()] )
            {
                dump('ok');
            }
        }


    }



    private function buildCmsLanguagesCache()
    {
        /** @var Manager $modelsManager */
        $modelsManager = $this->getModelsManager();
        $qb = $modelsManager->createBuilder();
        $qb->from(self::class);
        $qb->orderBy('is_primary DESC, position ASC');

        $entries = $qb->getQuery()->execute();
        $save = [];
        if ($entries->count()) {

            /** @var ModelLanguage $el */
            foreach ($entries as $el) {
                $save[$el->getIso()] = [
                    'id'         => $el->getId(),
                    'iso'        => $el->getIso(),
                    'title'       => $el->getTitle(),
                    'is_primary'    => $el->getIsPrimary(),
                    'position'    => $el->getPosition(),
                    'direction'    => $el->getDirection(),
                ];
            }
        }
        return $save;
    }

    public static function findCachedLanguages()
    {
        return CmsCache::getInstance()->get('languages');
    }

    public static function findCachedLanguagesIso()
    {
        $languages = self::findCachedLanguages();
        $iso_array = [];
        if (!empty($languages)) {
            foreach ($languages as $lang) {
                $iso_array[] = $lang['iso'];
            }
        }
        return $iso_array;
    }

    public static function findCachedByIso($iso)
    {
        $languages = self::findCachedLanguages();
        foreach ($languages as $lang) {
            if ($iso == $lang['iso']) {
                return $lang;
            }
        }
    }

    public static function cacheKey()
    {
        return HOST_HASH . md5('ModelLanguage::findCachedLanguages');
    }

    public function getUpperPosition()
    {
        $count = self::count();
        return $count;
    }

    public function setOnlyOnePrimary()
    {
        if ($this->getIsPrimary() == 1) //is_primary = 1 => all of is_primary = 0
        {
            $languages = $this::find();
            /** @var ModelLanguage $lang */
            foreach ($languages as $lang) {
                if ($lang->getId() != $this->getId()) {
                    $lang->setIsPrimary(0);
                    $lang->save();

                }
            }
            //dump($languages->toArray());
        }
        else //is_primary = 0
            {
            $primary = $this->findFirst("is_primary = '1'");
            if (!$primary) {
                $this->setIsPrimary(1);
                $this->save();

               // $this->getDI()->get('flash')->notice('There should always be a primary language');
            }
        }
    }

    public static function positionOptions()
    {
        $positionOptions = [];
        $previous = null;
        $passedself = false;

        $languages = self::find([
            'columns' => 'id, title, position'
        ])->toArray();

        foreach($languages as $language)
        {
            if(isset($previous))
            {
                $positionHtml = 'after '. (($passedself ? $language['title'] : $previous['title']));
            }
            else
            {
                $positionHtml = 'First';
            }

            $positionOptions[$language['position']] = $positionHtml;

            $previous = $language;
        }

        return $positionOptions;
    }

    public static function findAllForDataTable()
    {
        $langs = self::findCachedLanguages();

        $row = [];
        foreach($langs as $lang)
        {
            $col = [];
            if(is_array($lang))
            {
                foreach($lang as $key => $value)
                {
                    if($key == 'is_primary')
                    {
                        if($value)
                        {
                            $col[$key]['display'] = "Yes";
                        }
                        else
                        {
                            $col[$key]['display'] = "NO";
                        }
                        $col[$key]['value'] = $value;
                    }
                    else
                    {
                        $col[$key] = $value;
                    }

                }
            }
            $row[] = $col;
        }

        return $row;
    }
}