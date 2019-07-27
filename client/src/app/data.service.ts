import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Note } from "./note.model";

@Injectable({
  providedIn: "root"
})
export class DataService {
  private getNotesURL =
    "http://localhost/notes-manager/server/api.php?operation=getNotes";
    private addNoteURL =
    "http://localhost/notes-manager/server/api.php?operation=addNote";
    private updateNoteURL =
    "http://localhost/notes-manager/server/api.php?operation=updateNote";
    private deleteNoteURL =
    "http://localhost/notes-manager/server/api.php?operation=deleteNote";
  constructor(private http: HttpClient) {}
  public getNotes = () => {
    return this.http.get<any>(this.getNotesURL);
  };
  public addNote = (note) => {
    return this.http.post<Note>(this.addNoteURL, JSON.stringify(note));
  }
  public updateNote = (note) => {
    return this.http.post<Note>(this.updateNoteURL, JSON.stringify(note));
  }
  public deleteNote = (id) => {
    return this.http.post(this.deleteNoteURL, {id});
  }
}
