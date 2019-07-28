import { TestBed, async } from '@angular/core/testing';

import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { DataService } from './data.service';
import { browser } from 'protractor';
import { request } from 'https';

describe('DataService', () => {
  let service: DataService;
  let HttpMock : HttpTestingController;
  beforeEach(async(() => TestBed.configureTestingModule({imports:[
    HttpClientTestingModule
  ]})));

  beforeEach(() => {
    service = TestBed.get(DataService);
    HttpMock  = TestBed.get(HttpTestingController);
  })

  afterEach(() => {
    HttpMock.verify();
  })

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should fetch notes', () => {
    let dummyNotes = {"error":null,"ack":true,"data":[{"id":"49","comment":"gggg","dateTime":"1564320864000"},{"id":"50","comment":"d","dateTime":"1564320868000"},{"id":"51","comment":"ff","dateTime":"1564321074000"},{"id":"52","comment":"666","dateTime":"1564321077000"},{"id":"54","comment":"dd","dateTime":"1564323233000"}]};
    let notes = [];
    service.getNotes().subscribe(response => {
      notes = response.data;
      expect(notes.length).toBe(5);
    })

    const request = HttpMock.expectOne(`${service.getNotesURL}`)
    request.flush(dummyNotes);
  })
});
