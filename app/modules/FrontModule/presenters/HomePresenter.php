<?php

namespace FrontModule;
/**
 * Homepage presenter.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavNetteSandbox
 */
class HomePresenter extends \BaseModule\BasePresenter
{

	public function actionCreateDefaultUser() {
		$user = new \Entities\User('admin');
		$user->setPassword($this->getContext()->authenticator->calculateHash('mypassword'));
		$user->setEmail('your@eail.com')->setRole('admin');

		$this->em->persist($user);
		try {
			$this->em->flush();
		} catch(\PDOException $e) {
			dump($e);
			$this->terminate();
		} catch (\Doctrine\DBAL\DBALException $e) {
			dump($e);
			$this->terminate();
		}

		$this->sendResponse(new \Nette\Application\Responses\TextResponse('OK'));
		$this->terminate();
	}

	public function renderDefault() {
		$this->template->articles = $this->articleRepository->findAll();
	}

}
