import { Component } from '@angular/core';
import {  NavController, ModalController, LoadingController} from 'ionic-angular';
import { GalleryModal } from 'ionic-gallery-modal';
import { Http } from '@angular/http';
import 'rxjs/add/operator/map';

@Component({
  selector: 'page-galeria',
  templateUrl: 'galeria.html',
})
export class GaleriaPage {
  imagenes: any;
  loader:any;
  private images:any[] =  [];
  constructor(public navCtrl: NavController,
              private modalCtrl:ModalController,
              public loadingCtrl: LoadingController,
              private http:Http) {
    this.loader = this.loadingCtrl.create({
          content: "Espera"
        });
    this.loader.present();
    this.cargarImagenes();
  }

  public cargarImagenes(){
    this.http.get('http://app.tecnologiasalfa.com/api/getGaleria.php').map(res => res.json()).subscribe(data => {
      this.images = data;
      this.loader.dismiss();
    });
  }

  abrirModal(index:number){
    let modal = this.modalCtrl.create(GalleryModal, {
      photos: this.images,
      initialSlide: index
    });
    modal.present();
  }
}
