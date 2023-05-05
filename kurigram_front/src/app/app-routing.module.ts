import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './views/home/home.component';
import { ContactComponent } from './views/contact/contact.component';
import { EventsComponent } from './views/events/events.component';
import { LoginComponent } from './views/login/login.component';
import { SigninComponent } from './views/signin/signin.component';
import { ProfileComponent } from './views/profile/profile.component';
import { PostsComponent } from './views/posts/posts.component';
import { GenteComponent } from './views/gente/gente.component';

const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'home', component: HomeComponent },
  
  { path: 'contact', component: ContactComponent },
  { path: 'events', component: EventsComponent },
  { path: 'signin', component: SigninComponent },
  { path: 'logeo', component: LoginComponent },
  { path: 'profile', component: ProfileComponent },
  { path: 'posts', component: PostsComponent},
  { path: 'gente', component: GenteComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
