import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { StudentsListComponent } from './students-list/students-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {MyDatePickerModule} from 'mydatepicker';

@NgModule({
  declarations: [
    AppComponent,
    StudentsListComponent
  ],
  imports: [
    BrowserModule, FormsModule,
    ReactiveFormsModule, MyDatePickerModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
