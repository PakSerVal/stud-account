import { Injectable } from '@angular/core';
import {Headers, Http, Response} from '@angular/http';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import {Student} from './student';

@Injectable()
export class StudentsListService {

  constructor(private http: Http) { }

  public getSortedStudents(page: number, sortOption: string) {
    const students = this.http.get('/api/get-sorted-students/' + page + '/' + sortOption)
      .map(this.extractStudents);
    return students;
  }

  public getStudentsByFilter(filter): Observable<Student[]> {
    const body = JSON.stringify(filter);
    const headers = new Headers({ 'Content-Type': 'application/json;charset=utf-8' });
    return this.http.post('/api/get-students-by-filter/', body, { headers: headers })
      .map(this.extractStudents);
  }

  private extractStudents(response: Response) {
    const res = response.json();
    const students: Student[] = [];
    for (let i = 0; i < res.length; i++) {
      const student = new Student();
      student.fio = res[i].fio;
      student.email = res[i].email;
      student.phone = res[i].phone;
      student.address = res[i].address;
      student.status = res[i].status;
      student.course = res[i].course;
      student.order = res[i].orderNum;
      student.studyType = res[i].studyType;
      student.score = res[i].score;
      student.factId = res[i].factId;

      students.push(student);
    }
    return students;
  }
}

