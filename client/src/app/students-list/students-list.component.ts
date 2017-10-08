import { Component, OnInit } from '@angular/core';
import {IMyDpOptions, IMyDateRange} from 'mydatepicker';

@Component({
  selector: 'app-students-list',
  templateUrl: './students-list.component.html',
  styleUrls: ['./students-list.component.css']
})
export class StudentsListComponent implements OnInit {

  public myDatePickerOptions: IMyDpOptions = {
    // other options...
    dateFormat: 'dd.mm.yyyy',
    disableUntil: {year: 2016, month: 6, day: 26},
    disableSince: {year: 2016, month: 7, day: 26},
  };

  // Initialized to specific date (09.10.2018).
  public dateOption;

  constructor() { }

  ngOnInit() {
  }

}
