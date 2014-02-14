<?php

namespace Mayflower\TemplateTutorialBundle\Controller;

use eZ\Publish\Core\MVC\Symfony\Controller\Content\ViewController;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query;

class SubtreeController extends ViewController
{
    public function getArticles($location)
    {
        $searchService = $this->getRepository()->getSearchService();

        $criterion = array(
            new Criterion\ContentTypeIdentifier(['article']),
            new Criterion\Subtree( $location->pathString )
        );

        $query = new Query();
        $query->criterion = new Criterion\LogicalAnd(
            $criterion
        );

        $query->limit = 20;

        $articleResults = $searchService->findContent( $query );

        $articles = [];

        $locationService = $this->getRepository()->getLocationService();

        foreach($articleResults->searchHits as $hit) {
            //$loc = $locationService->loadLocation($hit->valueObject->versionInfo->contentInfo->mainLocationId);

            $articles[] =  $hit->valueObject;
        }

        return $this->render(
            'MayflowerTemplateTutorialBundle:list:article_list.html.twig',
            ['articles' => $articles, 'viewType' => 'linnea']
        );
    }
}
