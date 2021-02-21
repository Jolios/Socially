<?php 
class Message {
	private $user;
	private $con;

	public function __construct($con, $user){
		$this->con=$con;
		$this->user= new Utilisateur($con, $user);
	}
	public function getContactRecent(){
		$utlConnecte = $this->user->getPseudo();
		$req = mysqli_query($this->con,"SELECT envoye_a,envoye_de FROM message WHERE envoye_a = '$utlConnecte' OR envoye_de = '$utlConnecte' ORDER BY id DESC LIMIT 1");
		if(mysqli_num_rows($req)==0)
			return false;
		$tab=mysqli_fetch_array($req);
		$envoye_a=$tab['envoye_a'];
		$envoye_de=$tab['envoye_de'];
		if($envoye_a!=$utlConnecte)
			return $envoye_a;
		else
			return $envoye_de;
	}
	public function envoyerMessage($envoye_a,$contenu,$date){
		$utlConnecte=$this->user->getPseudo();
		$req=mysqli_query($this->con,"INSERT INTO message VALUES(NULL,'$envoye_a','$utlConnecte','$contenu','$date','non','non','non')");
		
	}
	public function getMessages($envoye_de){
		$utlConnecte=$this->user->getPseudo();
		$info="";
		$req=mysqli_query($this->con,"UPDATE message SET ouvert='oui' WHERE envoye_a='$utlConnecte' AND envoye_de='$envoye_de'");
		$req=mysqli_query($this->con,"SELECT * FROM message WHERE (envoye_a='$utlConnecte' AND envoye_de='$envoye_de') OR (envoye_de='$utlConnecte' AND envoye_a='$envoye_de')");
		while($tab=mysqli_fetch_array($req)){
			$envoye_a=$tab['envoye_a'];
			$envoye_de=$tab['envoye_de'];
			$contenu=$tab['contenu'];
			$div_top = ($envoye_a==$utlConnecte) ? "<div class='msg' id='vert'>":"<div class='msg' id='bleu'>";
			$info=$info.$div_top.$contenu."</div><br><br>";
		}
		return $info;
	}
	public function getDernierMessage($utlConnecte,$utl2){
		$tab_details = array();
		$req=mysqli_query($this->con,"SELECT contenu,envoye_a, date FROM message WHERE (envoye_a='$utlConnecte' AND envoye_de='$utl2') OR (envoye_a='$utl2' AND envoye_de='$utlConnecte') ORDER BY id DESC LIMIT 1");
		$tab=mysqli_fetch_array($req);
		$envoye_par=($tab['envoye_a']==$utlConnecte) ? "Ils ont dit: ":"Vous avez dit: ";
		$date_ajout=$tab['date'];

		$date_ajout=new DateTime($date_ajout);
		$date_courante=new DateTime(date("Y-m-d H:i:s"));
		$diff=$date_ajout->diff($date_courante);
		if($diff->y>=1){
			if($diff->y==1)
				$date_message="Il y a ". $diff->y . " an";
			else
				$date_message="Il y a ". $diff->y . " ans";
		}
		else if ($diff->m>=1){
			$jours="";				
			if($diff->d==1)
				$jours=$diff->d." jour";
			else if($diff->d>1)
				$jours=$diff->d." jours";
				$date_message="Il y a ".$diff->m." mois et ".$jours;
		}
		else if ($diff->d>=1){
			if($diff->d==1)
				$date_message="Hier";
			else 
				$date_message="Il y a ".$diff->d." jours";
		}
		else if($diff->h >= 1){
			if($diff->h == 1)
				$date_message = "Il y a ".$diff->h ." heure";
			else
				$date_message = "Il y a ".$diff->h." heures";

			
		}
		else if($diff->m >= 1){
			if($diff->m == 1)
				$date_message = "Il y a ".$diff->m ." minute";
			else
				$date_message = "Il y a ".$diff->m." minutes";

			
		}
		else {
			if($diff->s < 30)
				$date_message = "A l'instant";
			else
				$date_message = "Il y a ".$diff->s." secondes";

		}
		array_push($tab_details, $envoye_par);
		array_push($tab_details, $tab['contenu']);
		array_push($tab_details, $date_message);
		return $tab_details;
	}
	public function getConvos(){
		$utlConnecte=$this->user->getPseudo();
		$res="";
		$convos=array();
		$req = mysqli_query($this->con,"SELECT envoye_a,envoye_de FROM message WHERE envoye_a='$utlConnecte' OR envoye_de='$utlConnecte' ORDER BY id DESC");
		while($tab=mysqli_fetch_array($req)){
			$utl_a_ajout=($tab['envoye_a']!= $utlConnecte) ? $tab['envoye_a'] : $tab['envoye_de'];
			if(!in_array($utl_a_ajout, $convos)){
				array_push($convos, $utl_a_ajout);
			}
		}
		foreach ($convos as $pseudo) {
			$ob_utl = new Utilisateur($this->con,$pseudo);
			$details_dernier_msgs=$this->getDernierMessage($utlConnecte,$pseudo);

			$points= (strlen($details_dernier_msgs[1])>=12) ? "..." : "";
			$split=str_split($details_dernier_msgs[1],12);
			$split=$split[0].$points;

			$res.="<a href='messages.php?u=$pseudo'> 
					<div class='utl_t_msg'>
					<img src='".$ob_utl->getPhotoProfile()."' style='border-radius:5px;margin-right:5px;'>".$ob_utl->getNom()."<br>
					<span class='date_heure' id='gris'>".$details_dernier_msgs[2]."</span>
					<p id='gris' style='margin: 0'>".$details_dernier_msgs[0].$split."</p>
					</div>
					</a>";
		}
		return $res;

	}
}
?>

