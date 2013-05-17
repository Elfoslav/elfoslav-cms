<?php

namespace Entities;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Editable page
 *
 * @ORM\Entity(repositoryClass="Repositories\EditablePageRepository")
 * @ORM\Table(name="pages_editable")
 */
class EditablePage extends BasePage
{

}
