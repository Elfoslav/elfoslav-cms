<?php

namespace FrontModule;

use Doctrine\DBAL\Migrations\Migration;

/**
 * Homepage presenter.
 *
 * @author     Tomáš Hromník
 * @package    ElfoslavCMS
 */
class HomePresenter extends \BaseModule\BasePresenter
{

	public function actionCreateDefaultUser() {
		$name = 'admin';

		$userRepo = $this->em->getRepository('Entities\User');
		$existingUser = $userRepo->findOneBy(array('username' => $name));
		if($existingUser) {
			$this->sendResponse(new \Nette\Application\Responses\TextResponse('Default user already exists'));
			$this->terminate();
		}

		$user = new \Entities\User($name);
		$user->setPassword($this->getContext()->authenticator->calculateHash('mypassword'));
		$user->setEmail('your@eail.com');

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
		//$this->forward('Blog:');
		$this->template->articles = $this->articleRepository->findAll();
	}

	public function renderMigrate() {
		$migrationConfig = $this->getService('migrations.configuration');
		$migration = new Migration($migrationConfig);
		$sql = $migration->migrate();
		$count = count($sql);
		dump($sql);
		$this->sendResponse(new \Nette\Application\Responses\TextResponse($count. ' migrations executed.'));
		$this->terminate();
	}

}
