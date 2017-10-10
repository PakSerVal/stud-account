import { Component, OnInit } from '@angular/core';
import {IMyDpOptions, IMyDateRange} from 'mydatepicker';
import { StudentsListService } from './students-list.service';
import {Student} from './student';

@Component({
  selector: 'app-students-list',
  templateUrl: './students-list.component.html',
  styleUrls: ['./students-list.component.css']
})
export class StudentsListComponent implements OnInit {

  filter = {};
  filterDateKeys = [];

  totalCountStudents: number;
  page = 1;
  minDate;
  maxDate;
  yearsAvailable = [];
  monthesAvailable = {
    '1': ['1', '2', '3'],
    '2': ['4', '5', '6'],
    '3': ['7', '8', '9'],
    '4': ['10', '11', '12']
  };

  myDatePickerOptions: IMyDpOptions = {
    dateFormat: 'dd.mm.yyyy',
  };
  datePeriods = ['Год', 'Квартал', 'Месяц', 'День'];
  datePeriod = 'Год';

  optionsList = {date: false, course: false, status: false};
  dateOptionYear;
  dateOptionQuarter = '1';
  dateOptionMonth = '2';
  dateOptionDay = '2';
  statusOption = {admis: false, depart: false, trans: false, grad: false};
  courseOption = {first: false, second: false, third: false, fourth: false, fifth: false};

  studentColumns = ['ФИО', 'Email', 'Телефон', 'Статус', 'Курс', 'Приказ', 'Форма обучения', 'Баллы ЕГЭ'];
  studentTableColumnsView = {
    'ФИО': true, 'Email': true, 'Телефон': true, 'Статус': true,
    'Курс': true, 'Приказ': true, 'Форма обучения': true, 'Баллы ЕГЭ': true
  };
  studentsColumnMap = {
    'ФИО': 'fio', 'Email': 'email', 'Телефон': 'phone', 'Статус': 'status', 'Курс': 'course',
    'Приказ': 'order', 'Форма обучения': 'studyType', 'Баллы ЕГЭ': 'score'
  };

  sortOption = 'fio';

  studentList: Student[];
  constructor(private studentService: StudentsListService) { }

  ngOnInit() {
    this.getTotalCount();
    this.getStudentList();
    this.getMinAndMaxDate();
  }

  applyOptions() {
    if (this.optionsList.status) {
      const statuses = [];
      for (const statusKey in this.statusOption) {
        if (this.statusOption[statusKey]) {
          statuses.push(statusKey);
        }
      }
      this.filter['status'] = statuses;
    } else {
      delete this.filter['status'];
    }

    if (this.optionsList.course) {
      const courses = [];
      for (const courseKey in this.courseOption) {
        if (this.courseOption[courseKey]) {
          courses.push(courseKey);
        }
      }
      this.filter['course'] = courses;
    } else {
      delete this.filter['course'];
    }

    if (this.optionsList.date) {
      if (this.datePeriod === 'Год') {
        this.filter['date'] = {'period': 'year', 'year': this.dateOptionYear};
      } else if (this.datePeriod === 'Квартал') {
        this.filter['date'] = {'period': 'quarter', 'year': this.dateOptionYear, 'quarter': this.dateOptionQuarter};
      } else if (this.datePeriod === 'Месяц') {
        this.filter['date'] = {
          'period': 'month', 'year': this.dateOptionYear,
          'quarter': this.dateOptionQuarter, 'month': this.dateOptionMonth
        };
      } else if (this.datePeriod === 'День') {
        this.filter['date'] = {
          'period': 'day', 'day': this.dateOptionDay
        };
      }
      this.filterDateKeys = Object.keys(this.filter['date']);
    } else {
      delete this.filter['date'];
      this.filterDateKeys = [];
    }
    this.getStudentList();
    this.getTotalCount();
  }
  sort() {
    this.getStudentList();
  }
  paginate(event) {
    this.page = event.page;
    this.getStudentList();
  }
  getStudentList() {
    this.studentService.getStudentsByFilter(this.page, this.sortOption, this.filter).subscribe(
      students => this.studentList = students
    );
  }
  getTotalCount() {
    this.studentService.getStudentsTotalCount(this.filter).subscribe(
      totalCountStudents => this.totalCountStudents = totalCountStudents
    );
  }
  getMinAndMaxDate() {
    this.studentService.getMinAndMaxDate().subscribe(
      dateObject => {
        this.minDate = dateObject.min;
        this.maxDate = dateObject.max;
        for (let year = this.minDate.year; year <= this.maxDate.year; year++) {
          this.yearsAvailable.push(year);
        }
        this.dateOptionYear = this.minDate.year;
        this.myDatePickerOptions['disableUntil'] = {year: this.minDate.year, month: this.minDate.month, day: this.minDate.day};
        this.myDatePickerOptions['disableSince'] = {year: this.maxDate.year, month: this.maxDate.month, day: this.maxDate.day};
      }
    );
  }

  onChangeQuarter() {
    this.dateOptionMonth = this.monthesAvailable[this.dateOptionQuarter][0];
  }
}
