<?php
namespace App\Service\Handler\<%= className %>;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Service\Command\AbstractCommand;
use App\Service\Handler\AbstractHandler;

class DeleteHandler extends AbstractHandler
{
    /**
     *
     * {@inheritdoc}
     * @see \App\Service\Handler\AbstractHandler::handle()
     */
    public function handle(AbstractCommand $command)
    {
        $error = $this->validator->validate($command);
        if ($error->count() == 0) {
            $entity = $this->em->getRepository('App:<%= className %>')->find($command->id);
            if (empty($entity)) {
                throw new NotFoundHttpException("The id {$command->id} record not found");
            }
            $this->em->remove($entity);
            $this->em->flush();
            return [
                "Iten {$command->id} deleted with success."
            ];
        }
        return $error;
    }
}
