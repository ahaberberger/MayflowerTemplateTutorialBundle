<?php

namespace Mayflower\TemplateTutorialBundle\Controller;

use eZ\Publish\Core\MVC\Symfony\Controller\Content\ViewController;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Location;

class SubtreeController extends ViewController
{
    /**
     * @param Location $location
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

        $articleResults = $searchService->findContent( $query );

        $articles = [];

        foreach($articleResults->searchHits as $hit) {

            $articles[] =  $hit->valueObject;
        }

        $response = $this->buildResponse(
            sprintf('%s_%d', __METHOD__, $location->id),
            $location->contentInfo->modificationDate
        );

        return $this->render(
            'MayflowerTemplateTutorialBundle:list:article_list.html.twig',
            ['articles' => $articles, 'viewType' => 'listitem'],
            $response
        );
    }
}
