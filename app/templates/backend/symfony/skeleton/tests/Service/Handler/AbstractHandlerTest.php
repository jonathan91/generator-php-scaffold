<?php
namespace App\Tests\Service\Handler;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Handler\Tipo\PostHandler as TipoPostHandler;
use App\Service\Command\Tipo\PostCommand as TipoPostCommand;
use App\Service\Handler\Estado\PostHandler as EstadoPostHandler;
use App\Service\Command\Estado\PostCommand as EstadoPostCommand;
use App\Service\Command\Acao\PostCommand as AcaoPostCommand;
use App\Service\Handler\Acao\PostHandler as AcaoPostHandler;
use App\Service\Command\Documento\PostCommand as DocumentoPostCommand;
use App\Service\Handler\Documento\PostHandler as DocumentoPostHandler;
use App\Service\Command\Historico\PostCommand as HistoricoPostCommand;
use App\Service\Handler\Historico\PostHandler as HistoricoPostHandler;
use App\Service\Command\Comentario\PostCommand as ComentarioPostCommand;
use App\Service\Handler\Comentario\PostHandler as ComentarioPostHandler;
use App\Entity\Tipo;
use App\Entity\Estado;
use App\Entity\Acao;
use App\Entity\Documento;
use App\Entity\Historico;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;

/**
 *
 * @author basis
 *        
 */
abstract class AbstractHandlerTest extends KernelTestCase
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     *
     * {@inheritdoc}
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    /**
     *
     * @return \App\Entity\Tipo
     */
    protected function addTipo()
    {
        $command = new TipoPostCommand();
        $command->descricao = 'teste';
        $handle = new TipoPostHandler($this->entityManager, $this->validator);
        return $handle->handle($command);
    }

    /**
     *
     * @param Tipo $tipo
     * @return \App\Entity\Estado
     */
    protected function addEstado(Tipo $tipo)
    {
        $command = new EstadoPostCommand();
        $command->descricao = 'teste';
        $command->ordem = 1;
        $command->status = true;
        $command->tipo = $tipo;
        $handle = new EstadoPostHandler($this->entityManager, $this->validator);
        return $handle->handle($command);
    }

    /**
     *
     * @param Estado $origem
     * @param Estado $destino
     * @param boolean $comentario
     * @return \App\Entity\Acao
     */
    protected function addAcao(Estado $origem, Estado $destino, $comentario = false)
    {
        $command = new AcaoPostCommand();
        $command->descricao = 'teste';
        $command->comentario = $comentario;
        $command->status = true;
        $command->origem = $origem;
        $command->destino = $destino;
        $handle = new AcaoPostHandler($this->entityManager, $this->validator);
        return $handle->handle($command);
    }

    /**
     *
     * @param Estado $estado
     * @param Tipo $tipo
     * @return \App\Entity\Documento
     */
    protected function addDocumento(Estado $estado, Tipo $tipo)
    {
        $command = new DocumentoPostCommand();
        $command->descricao = 'teste';
        $command->dataInclusao = new \DateTime();
        $command->tipo = $tipo;
        $command->estado = $estado;
        $handle = new DocumentoPostHandler($this->entityManager, $this->validator);
        return $handle->handle($command);
    }

    /**
     *
     * @param Acao $acao
     * @param Documento $documento
     * @return array
     */
    protected function addHistorico(Acao $acao, Documento $documento)
    {
        $command = new HistoricoPostCommand();
        $command->usuario = 'codigo_usuario';
        $command->dataTramite = new \DateTime();
        $command->acao = $acao;
        $command->documento = $documento;
        $handle = new HistoricoPostHandler($this->entityManager, $this->validator);
        return $handle->handle($command);
    }

    /**
     *
     * @param Acao $acao
     * @param Documento $documento
     * @return array
     */
    protected function addComentario(Historico $historico)
    {
        $command = new ComentarioPostCommand();
        $command->historico = $historico;
        $command->dataInclusao = new \DateTime();
        $command->descricao = 'teste';
        $handle = new ComentarioPostHandler($this->entityManager, $this->validator);
        return $handle->handle($command);
    }
}

