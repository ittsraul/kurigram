import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { CommonModule } from '@angular/common';


import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HeaderComponent } from './components/header/header.component';
import { ContactComponent } from './views/contact/contact.component';
import { EventsComponent } from './views/events/events.component';
import { HomeComponent } from './views/home/home.component';

import { SigninComponent } from './views/signin/signin.component';
import { ProfileComponent } from './views/profile/profile.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { EventComponent } from './views/events/event/event.component';
import { PostsComponent } from './views/posts/posts.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatCardModule } from '@angular/material/card';
import { PostFormComponent } from './views/new-post/new-post.component';
import { TimelineComponent } from './views/timeline/timeline.component';
import { GenteComponent } from './views/gente/gente.component';
import { IniciarSesionComponent } from './views/iniciar-sesion/iniciar-sesion.component';
import { GuardControlGuard } from './guard-control.guard';




@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    ContactComponent,
    EventsComponent,
    HomeComponent,
    SigninComponent,
    ProfileComponent,
    EventComponent,
    PostsComponent,
    PostFormComponent,
    TimelineComponent,
    GenteComponent,
    IniciarSesionComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    HttpClientModule,
    BrowserAnimationsModule,
    MatCardModule,
    CommonModule,
    ReactiveFormsModule,
  ],
  providers: [GuardControlGuard],
  bootstrap: [AppComponent]
})
export class AppModule { }
