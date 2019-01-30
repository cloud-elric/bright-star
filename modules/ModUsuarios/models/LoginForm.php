<?php

namespace app\modules\ModUsuarios\models;

use Yii;
use yii\base\Model;
use app\models\EntUsuariosBloqueados;
use yii\db\Expression;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *          
 */
class LoginForm extends Model {
	public $username;
	public $password;
	public $rememberMe = true;
	public $userEncontrado;
	private $minutosBloqueado = 15;
	private $numIntentos = 5;
	private $_user = false;

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Nombre de Usuario',
            'password' => 'Contraseña'
        ];
    }
	
	/**
	 *
	 * @return array the validation rules.
	 */
	public function rules() {
		return [
				// username and password are both required
				[ 
						[ 
								'username',
								'password' 
						],
						'required',
						'on' => 'login',
						'message'=>'Campo requerido' 
				],
				// username es requerido para recuperar la contraseña
				[ 
						[ 
								'username' 
						],
						'required',
						'on' => 'recovery',
						'message'=>'Campo requerido' 
				],
				[ 
						[ 
								'username' 
						],
						'validateUsuario',
						'on' => 'recovery',
				],
				[ 
						[ 
								'username' 
						],
						'trim' 
				],
				//['username','email', 'message'=>'Debe agregar un email válido'],
				
				// rememberMe must be a boolean value
				[ 
						'rememberMe',
						'boolean' 
				],
				// password is validated by validatePassword()
				[ 
						'password',
						'validatePassword',
						'on' => 'login' 
				] 
		];
	}
	
	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute
	 *        	the attribute currently being validated
	 * @param array $params
	 *        	the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params) {

		if (! $this->hasErrors ()) {
			$user = $this->getUser ();

			if(!$user){
				$this->addError ( $attribute, 'Usuario o contraseña incorrectos.' );
				return;
			}

			$isUsuarioBloqueado = EntUsuariosBloqueados::find()->where(['id_usuario'=>$user->id_usuario, "b_bloqueado"=>1])
			->andWhere(["<=", new Expression("TIMESTAMPDIFF(MINUTE,fch_bloqueo,NOW())"), $this->minutosBloqueado])
			->one();

			if($isUsuarioBloqueado){
				$this->addError ( $attribute, 'Usuario bloqueado.' );
			}
			$usuarioIntentando = EntUsuariosBloqueados::find()
				->where([
					'id_usuario'=>$user->id_usuario, 
					"b_bloqueado"=>0])
				->one();

			if(!$usuarioIntentando){
				$usuarioIntentando = new EntUsuariosBloqueados();
				$usuarioIntentando->id_usuario = $user->id_usuario;
				$usuarioIntentando->fch_bloqueo = Utils::getFechaActual();
			}
			
			if (! $user || ! $user->validatePassword ( $this->password )) {
				$usuarioIntentando->num_intentos++;
				if($usuarioIntentando->num_intentos==$this->numIntentos){
					$usuarioIntentando->fch_bloqueo = Utils::getFechaActual();
					$usuarioIntentando->b_bloqueado = 1;
				} 

				$usuarioIntentando->save();
				
				$this->addError ( $attribute, 'Usuario o contraseña incorrectos.' );
			}else{
				if($usuarioIntentando){
					$usuarioIntentando->delete();
				}
			}
		}
	}

	// public function validateBloqueo(){
	// 	if (! $this->hasErrors ()) {
	// 		$user = $this->getUser ();

	// 		if (! $user || ! $user->validatePassword ( $this->password )) {
	// 			$this->addError ( $attribute, 'Usuario o contraseña incorrectos.' );
	// 		}
	// 	}
	// }

	
	/**
	 * Valida que el usuario exista
	 */
	public function validateUsuario($attribute, $params) {
		$this->userEncontrado = $this->getUser ();
		
		if (empty($this->userEncontrado)) {
			$this->addError ( $attribute, 'No existe una cuenta asociada al corro electronico ingresado.' );
		}
	}
	
	/**
	 * Logs in a user using the provided username and password.
	 *
	 * @return boolean whether the user is logged in successfully
	 */
	public function login() {
		if ($this->validate ()) {
			return Yii::$app->user->login ( $this->getUser (), $this->rememberMe ? 3600 * 24 * 30 : 0 );
		}
		return false;
	}
	
	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser() {
		if ($this->_user === false) {
			$this->_user = EntUsuarios::findByEmail ( $this->username );
			
		}
		
		return $this->_user;
	}
}
