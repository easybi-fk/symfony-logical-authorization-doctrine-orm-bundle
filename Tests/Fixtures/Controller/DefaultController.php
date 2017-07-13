<?php

namespace Ordermind\LogicalAuthorizationDoctrineORMBundle\Tests\Fixtures\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ordermind\LogicalAuthorizationDoctrineORMBundle\Services\Decorator\EntityDecoratorInterface;
use Ordermind\LogicalAuthorizationDoctrineORMBundle\Tests\Fixtures\Entity\Misc\TestEntity;

class DefaultController extends Controller {

  /**
    * @Route("/count-unknown-result", name="count_unknown_result")
    * @Method({"GET"})
    */
  public function countUnknownResultAction(Request $request) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $result = $operations->getUnknownResult();
    return new Response(count($result));
  }

  /**
    * @Route("/find-single-entity-result/{id}", name="find_single_entity_result")
    * @Method({"GET"})
    */
  public function findSingleEntityResultAction(Request $request, $id) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $result = $operations->getSingleEntityResult($id);
    return new JsonResponse((bool) $result);
  }

  /**
    * @Route("/count-multiple-entity-result", name="count_multiple_entity_result")
    * @Method({"GET"})
    */
  public function countMultipleEntityResultAction(Request $request) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $result = $operations->getMultipleEntityResult();
    return new Response(count($result));
  }

  /**
    * @Route("/count-entities-lazy", name="test_count_entities_lazy")
    * @Method({"GET"})
    */
  public function countEntitiesLazyLoadAction(Request $request) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $collection = $operations->getLazyLoadedEntityResult();
    return new Response(count($collection));
  }

  /**
    * @Route("/create-entity", name="create_entity")
    * @Method({"GET"})
    */
  public function createEntityAction(Request $request) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity();
    return new JsonResponse(is_object($entityDecorator) && $entityDecorator instanceof EntityDecoratorInterface);
  }

  /**
    * @Route("/call-method-getter", name="call_method_getter")
    * @Method({"GET"})
    */
  public function callMethodGetterAction(Request $request) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity(null, true);
    $operations->callMethodSetter($entityDecorator, true);

    return new Response($operations->callMethodGetter($entityDecorator));
  }

  /**
    * @Route("/call-method-getter-author", name="call_method_getter_author")
    * @Method({"GET"})
    */
  public function callMethodGetterAuthorAction(Request $request) {
    $user = $this->get('logauth.service.helper')->getCurrentUser();
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity($user, true);
    $operations->callMethodSetter($entityDecorator, true);

    return new Response($operations->callMethodGetter($entityDecorator));
  }

  /**
    * @Route("/call-method-setter", name="call_method_setter")
    * @Method({"GET"})
    */
  public function callMethodSetterAction(Request $request) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity(null, true);
    $operations->callMethodSetter($entityDecorator);

    return new Response($operations->callMethodGetter($entityDecorator, true));
  }

  /**
    * @Route("/call-method-setter-author", name="call_method_setter_author")
    * @Method({"GET"})
    */
  public function callMethodSetterAuthorAction(Request $request) {
    $user = $this->get('logauth.service.helper')->getCurrentUser();
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity($user, true);
    $operations->callMethodSetter($entityDecorator);

    return new Response($operations->callMethodGetter($entityDecorator, true));
  }

  /**
    * @Route("/save-entity-create", name="save_entity_create")
    * @Method({"GET"})
    */
  public function saveEntityCreateAction(Request $request) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $operations->createTestEntity();
    $result = $operations->getMultipleEntityResult(true);
    return new Response(count($result));
  }

  /**
    * @Route("/save-entity-update", name="save_entity_update")
    * @Method({"GET"})
    */
  public function saveEntityUpdateAction(Request $request) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity(null, true);
    $operations->callMethodSetter($entityDecorator, true);
    $entityDecorator->save();
    $entityDecorator->getEntityManager()->detach($entityDecorator->getEntity());
    $persistedEntityDecorator = $operations->getSingleEntityResult($entityDecorator->getEntity()->getId(), true);
    return new Response($operations->callMethodGetter($persistedEntityDecorator, true));
  }

  /**
    * @Route("/save-entity-update-author", name="save_entity_update_author")
    * @Method({"GET"})
    */
  public function saveEntityUpdateAuthorAction(Request $request) {
    $user = $this->get('logauth.service.helper')->getCurrentUser();
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity($user, true);
    $operations->callMethodSetter($entityDecorator, true);
    $entityDecorator->save();
    $entityDecorator->getEntityManager()->detach($entityDecorator->getEntity());
    $persistedEntityDecorator = $operations->getSingleEntityResult($entityDecorator->getEntity()->getId(), true);
    return new Response($operations->callMethodGetter($persistedEntityDecorator, true));
  }

  /**
    * @Route("/delete-entity", name="delete_entity")
    * @Method({"GET"})
    */
  public function deleteEntityAction(Request $request) {
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity(null, true);
    $entityDecorator->delete();
    $result = $operations->getMultipleEntityResult(true);
    return new Response(count($result));
  }

  /**
    * @Route("/delete-entity-author", name="delete_entity_author")
    * @Method({"GET"})
    */
  public function deleteEntityAuthorAction(Request $request) {
    $user = $this->get('logauth.service.helper')->getCurrentUser();
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity($user, true);
    $entityDecorator->delete();
    $result = $operations->getMultipleEntityResult(true);
    return new Response(count($result));
  }

  /**
    * @Route("/get-available-actions", name="get_available_actions")
    * @Method({"GET"})
    */
  public function getAvailableActionsAction(Request $request) {
    $user = $this->get('logauth.service.helper')->getCurrentUser();
    $operations = $this->get('test_entity_operations');
    $operations->setRepositoryDecorator($this->get($request->get('repository_decorator_service')));
    $entityDecorator = $operations->createTestEntity($user, true);
    $result = $entityDecorator->getAvailableActions($user);
    return new JsonResponse($result);
  }

  /**
   * @Route("/repository-decorator-create", name="test_repository_decorator_create")
   * @Method({"GET"})
   */
  public function repositoryDecoratorCreateAction(Request $request) {
    $entityDecorator = $this->get('repository.test_entity')->create()->save();
    return new Response('');
  }

  /**
   * @Route("/load-test-entity/{id}", name="load_test_entity", options={
   * "logauth_permissions": {
   *   "role": "ROLE_ADMIN"
   * }})
   * @Method({"GET"})
   */
  public function loadTestEntityAction(Request $request, TestEntity $testEntity = null) {
    return new Response(get_class($testEntity));
  }
}
