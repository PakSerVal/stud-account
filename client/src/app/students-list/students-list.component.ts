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

  public myDatePickerOptions: IMyDpOptions = {
    dateFormat: 'dd.mm.yyyy'
  };
  datePeriods = ['Год', 'Квартал', 'Месяц', 'День'];
  datePeriod = 'Год';

  optionsList = {date: false, course: false, status: false};
  dateOptionYear = '2016';
  dateOptionQuarter = '1';
  dateOptionMonth = '2';
  dateOptionDay = '2';
  statusOption = {admis: false, depart: false, trans: false, grad: false};
  courseOption = {first: false, second: false, third: false, fourth: false, fifth: false};

  studentColumns = ['ФИО', 'Email', 'Телефон', 'Адрес', 'Статус', 'Курс', 'Приказ', 'Форма обучения', 'Баллы ЕГЭ'];
  studentTableColumnsView = {
    'ФИО': true, 'Email': true, 'Телефон': true, 'Адрес': true, 'Статус': true,
    'Курс': true, 'Приказ': true, 'Форма обучения': true, 'Баллы ЕГЭ': true
  };
  studentsColumnMap = {
    'ФИО': 'fio', 'Email': 'email', 'Телефон': 'phone', 'Адрес': 'address', 'Статус': 'status', 'Курс': 'course',
    'Приказ': 'order', 'Форма обучения': 'studyType', 'Баллы ЕГЭ': 'score'
  };

  sortOption = 'fio';

  studentList: Student[];
  constructor(private studentService: StudentsListService) { }

  ngOnInit() {
    this.getStudentList();
  }

  applyOptions() {
    const filter = {};

    if (this.optionsList.status) {
      const statuses = [];
      for (const statusKey in this.statusOption) {
        if (this.statusOption[statusKey]) {
          statuses.push(statusKey);
        }
      }
      filter['status'] = statuses;
    }

    if (this.optionsList.course) {
      const courses = [];
      for (const courseKey in this.courseOption) {
        if (this.courseOption[courseKey]) {
          courses.push(courseKey);
        }
      }
      filter['course'] = courses;
    }

    if (this.optionsList.date) {
      if (this.datePeriod === 'Год') {
        filter['date'] = {'period': 'year', 'year': this.dateOptionYear};
      } else if (this.datePeriod === 'Квартал') {
        filter['date'] = {'period': 'quarter', 'year': this.dateOptionYear, 'quarter': this.dateOptionQuarter};
      } else if (this.datePeriod === 'Месяц') {
        filter['date'] = {
          'period': 'month', 'year': this.dateOptionYear,
          'quarter': this.dateOptionQuarter, 'month': this.dateOptionQuarter
        };
      } else if (this.datePeriod === 'День') {
        filter['date'] = {
          'period': 'day', 'day': this.dateOptionDay
        };
      }
    }

    this.studentService.getStudentsByFilter(filter).subscribe(
      students => this.studentList = students
    );
  }

  sort() {
    this.getStudentList();
  }
  paginate(event) {
    this.getStudentList(event.page + 1);
  }

  getStudentList(page = 1) {
    this.studentService.getSortedStudents(page, this.sortOption).subscribe(
      students => this.studentList = students
    );
  }
}
