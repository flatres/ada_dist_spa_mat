
#region Using Directives

// In-Built assemblies

using System;
using System.Collections.Generic;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Configuration;
using System.Data.Sql;
using System.Data.SqlClient;

// iSAMS assemblies

using iSAMS.Security;
using iSAMS.Services;
using iSAMS.Entities;
using ComponentArt.Web.UI;

// 3rd Party assemblies

#endregion Using Directives

public partial class custommodules_utilities_main_all_absence : SecurePage
{

    #region Global Variables

    bool bStopProcessing = false;

    #endregion Global Variables

    #region Page Events

    /// <summary>
    /// PreInit
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    protected void Page_PreInit(object sender, EventArgs e)
    {
        MSM = new iSAMS.Security.ModuleSecurityManager("iSAMS_CUSTOMUTILITIES", Global.SystemVars.SYS_UserSecurity);
    }

    /// <summary>
    /// Init
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    protected void Page_Init(object sender, EventArgs e)
    {
        infCriticalError.ImageDir = Global.DynamicVars.txtImageDir + "16/";

        if (!bStopProcessing)
            RegisterClientScripts();

        if (!bStopProcessing)
            WireUpControls();

        if (!bStopProcessing)
            BindData();

        if (!bStopProcessing)
            ApplyStyling();

        populateteacherbox(sender, e);
        ddlbeaks.SelectedValue = iSAMS.Runtime.Context.Current.LoggedInUser.TxtUserCode;
        if (!Page.IsPostBack)
        {
            loguser(sender, e);
        }
    }

    /// <summary>
    /// Load
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>


    #endregion Page Events

    #region UI Generation

    /// <summary>
    /// This method contains all client script registration
    /// </summary>
    private void RegisterClientScripts()
    {

    }

    /// <summary>
    /// This method wires up control events and properties
    /// </summary>
    private void WireUpControls()
    {
    }

    /// <summary>
    /// This method retrieves any data from the database and generates the appropriate UI content
    /// </summary>
    private void BindData()
    {

        PupilManagementPupilsService pupilsService = new PupilManagementPupilsService();
        TList<PupilManagementPupils> pupilsList = pupilsService.GetBySystemStatus(PupilManagementPupils.SystemStatus.Current);

        foreach (PupilManagementPupils pupil in pupilsList)
        {
            //Response.Write(pupil.TxtForename + "<br/>");
        }

        pupilsList.Dispose();
    }

    /// <summary>
    /// This method applies styling attributes to page elements and controls
    /// </summary>
    private void ApplyStyling()
    {
    }

    #endregion UI Generation

    #region Methods

    #endregion Methods
    protected void loguser(object sender, EventArgs e)
    {
        string sql = "INSERT INTO mccustom.dbo.TblUtilitiesLog ";
        sql = sql + "(User_Code, txtPage, dteLog) ";
        sql = sql + "VALUES('" + iSAMS.Runtime.Context.Current.LoggedInUser.TxtUserCode + "', ";
        sql = sql + "'abs', GETDATE());";
        dslog.InsertCommand = sql;
        //dslog.Insert();
        //Routine disabled on 22 June 2013
    }
    protected void populateteacherbox(object sender, EventArgs e)
    {
        string sql = "SELECT surname + ', ' + title + ' ' + firstname as teachername, ";
        sql = sql + "User_Code FROM TblStaff ";
        sql = sql + "WHERE systemstatus=1 AND TeachingStaff=1 ORDER BY teachername;";
        dsbeaks.SelectCommand = sql;
    }
    protected void show(object sender, EventArgs e)
    {
        string sql = "SELECT p.txtSurname + ', ' + p.txtForename as name, ";
        sql = sql + "ot.reason, ";
        sql = sql + "tt.txtSetCode, ";
        sql = sql + "convert(varchar, ot.st, 106) as date, ";
        sql = sql + "tt.txtPeriodName, ";
        sql = sql + "CASE ot.reason WHEN 'Music lesson' THEN LEFT(convert(varchar, ot.st, 8),5) ";
        sql = sql + "WHEN 'Learning Support' THEN LEFT(convert(varchar(8), ot.st, 8),5) ELSE '' END as clashstart, ";
        sql = sql + "CASE ot.reason WHEN 'Music lesson' THEN LEFT(convert(varchar, ot.et, 8),5) ";
        sql = sql + "WHEN 'Learning Support' THEN LEFT(convert(varchar, ot.et, 8),5) ELSE '' END as clashend ";
        sql = sql + "FROM ";
        sql = sql + "TblPupilManagementPupils p, ";
        //---------------------------------
        //--THE FOLLOWING IS THE TT TABLE--
        //---------------------------------
        //LOWER SCHOOL
        sql = sql + "(SELECT sl.txtSchoolID, ";
        sql = sql + "s.txtSetCode, sched.intPeriod, ";
        sql = sql + "tap.dteStart, tap.dteFinish, tap.txtPeriodName ";
        sql = sql + "FROM ";
        sql = sql + "(SELECT s.TblTeachingManagerSetsID, s.txtSetCode, s.txtTeacher FROM TblTeachingManagerSets s ";
        sql = sql + " UNION ";
        sql = sql + " SELECT s.TblTeachingManagerSetsID, s.txtSetCode, sat.txtTeacher FROM TblTeachingManagerSets s, ";
        sql = sql + " TblTeachingManagerSetAssociatedTeachers sat ";
        sql = sql + " WHERE s.TblTeachingManagerSetsID=sat.intSetID ";
        sql = sql + " ) s, ";
        sql = sql + "TblTeachingmanagerSetLists sl, ";
        sql = sql + "TblTimetableManagerSchedule sched, ";
        sql = sql + "(SELECT * FROM ";
        sql = sql + " mccustom.dbo.TblAbsencePeriods ";
        sql = sql + " WHERE boolLS='True' ";
        sql = sql + " AND boolTermTime='True' ";
        sql = sql + " ) tap ";
        sql = sql + "WHERE s.TblTeachingmanagerSetsID=sl.intSetID ";
        sql = sql + "AND sched.txtCode=s.txtSetCode ";
        sql = sql + "AND tap.intPeriod=sched.intPeriod ";
        sql = sql + "AND LEFT(s.txtSetCode, 1) IN ('S','R', 'H') ";
        sql = sql + "AND sched.txtTeacher='" + ddlbeaks.SelectedValue + "' ";
        sql = sql + "AND s.txtTeacher='" + ddlbeaks.SelectedValue + "' ";

        sql = sql + "UNION ";
        //UPPER SCHOOL
        sql = sql + "SELECT sl.txtSchoolID, ";
        sql = sql + "s.txtSetCode, sched.intPeriod, ";
        sql = sql + "tap.dteStart, tap.dteFinish, tap.txtPeriodName ";
        sql = sql + "FROM ";
        sql = sql + "(SELECT s.TblTeachingManagerSetsID, s.txtSetCode, s.txtTeacher FROM TblTeachingManagerSets s ";
        sql = sql + " UNION ";
        sql = sql + " SELECT s.TblTeachingManagerSetsID, s.txtSetCode, sat.txtTeacher FROM TblTeachingManagerSets s, ";
        sql = sql + " TblTeachingManagerSetAssociatedTeachers sat ";
        sql = sql + " WHERE s.TblTeachingManagerSetsID=sat.intSetID ";
        sql = sql + ") s, ";
        sql = sql + "TblTeachingmanagerSetLists sl, ";
        sql = sql + "TblTimetableManagerSchedule sched, ";
        sql = sql + "(SELECT * FROM ";
        sql = sql + "mccustom.dbo.TblAbsencePeriods ";
        sql = sql + "WHERE boolUS='True' ";
        sql = sql + "AND boolTermTime='True' ";
        sql = sql + ") tap ";
        sql = sql + "WHERE s.TblTeachingmanagerSetsID=sl.intSetID ";
        sql = sql + "AND sched.txtCode=s.txtSetCode ";
        sql = sql + "AND tap.intPeriod=sched.intPeriod ";
        sql = sql + "AND LEFT(s.txtSetCode, 1) IN ('L','U') ";
        sql = sql + "AND sched.txtTeacher='" + ddlbeaks.SelectedValue + "' ";
        sql = sql + "AND s.txtTeacher='" + ddlbeaks.SelectedValue + "' ";

        //LOWER SCHOOL FORMS
        sql = sql + "UNION ";
        sql = sql + "SELECT p.txtSchoolID, f.txtTimetableCode, ";
        sql = sql + "sched.intPeriod, ";
        sql = sql + "tap.dteStart, tap.dteFinish, tap.txtPeriodName ";
        sql = sql + "FROM TblPupilManagementPupils p, ";
        sql = sql + "TblTeachingManagerSubjectForms f, ";
        sql = sql + "(SELECT * FROM ";
        sql = sql + "   mccustom.dbo.TblAbsencePeriods ";
        sql = sql + "   WHERE boolLS='True' ";
        sql = sql + "   AND boolTermTime='True' ";
        sql = sql + "   ) tap, ";
        sql = sql + "TblTimetableManagerSchedule sched ";
        sql = sql + "WHERE f.txtForm=p.txtForm ";
        sql = sql + "AND sched.txtCode=f.txtTimetableCode ";
        sql = sql + "AND tap.intPeriod=sched.intPeriod ";
        sql = sql + "AND LEFT(f.txtTimetableCode, 1) IN ('S','R', 'H') ";
        sql = sql + "AND sched.txtTeacher='" + ddlbeaks.SelectedValue + "' ";
        sql = sql + "AND f.txtTeacher='" + ddlbeaks.SelectedValue + "' ";


        sql = sql + ") tt, ";
        //---------------------------------

        //---------------------------------
        //--THE OTHER ACTIVITIES TABLE
        //---------------------------------
        //MUSIC LESSONS
        sql = sql + "(SELECT xlh.txtSchoolID, xlh.dteDayTime as st, ";
        sql = sql + " DATEADD(mi, 35, xlh.dteDayTime) as et, 'Music lesson' as reason ";
        sql = sql + " FROM mccustom.dbo.TblXLHistory xlh, ";
        sql = sql + " mccustom.dbo.TblXLLessons xll ";
        sql = sql + " WHERE xlh.intLesson=xll.TblXLLessonsID ";
        sql = sql + " AND xll.txtDept='Music' ";
        //LEARNING SUPPORT LESSONS
        sql = sql + " UNION ";
        sql = sql + " SELECT xlh.txtSchoolID, xlh.dteDayTime as st, ";
        sql = sql + " DATEADD(mi, 35, xlh.dteDayTime) as et, 'Learning Support' as reason ";
        sql = sql + " FROM mccustom.dbo.TblXLHistory xlh, ";
        sql = sql + " mccustom.dbo.TblXLLessons xll ";
        sql = sql + " WHERE xlh.intLesson=xll.TblXLLessonsID ";
        sql = sql + " AND xll.txtDept='LS' ";
        //TRIPS
        sql = sql + "UNION ";
        sql = sql + "SELECT tp.txtSchoolID, t.departdatetime as st, t.returndatetime as et, ";
        sql = sql + "t.title as reason ";
        sql = sql + "FROM mccustom.dbo.TblTripMembers tp, ";
        sql = sql + "mccustom.dbo.TblTrips t ";
        sql = sql + "WHERE t.TblTripsID=tp.intTripID ";
        //sql = sql + "AND t.departdatetime>=GETDATE() ";

        //OPEN DAYS
        sql += "UNION ";
        sql += "SELECT txtSchoolID, dteDate as st, ";
        sql += "DATEADD(hh, 24, dteDate) as et, 'Open Day (' + txtUniversity + ')' as reason ";
        sql += "FROM mccustom.dbo.TblCareersOpenDays ";
        sql += "WHERE dteDate>=GETDATE() ";

        //MISCELLANEOUS
        sql = sql + "UNION ";
        sql = sql + "SELECT txtSchoolID collate database_default as txtSchoolID, dteStart as st, dteFinish as et, ";
        sql = sql + "txtReason collate database_default as reason ";
        sql = sql + "FROM mccustom.dbo.TblAbsenceMiscell ";
        //sql = sql + "WHERE dteStart>=GETDATE() ";
        sql = sql + ") ot ";
        //---------------------------------

        sql = sql + "WHERE tt.txtSchoolID=ot.txtSchoolID ";
        sql = sql + "AND p.txtSchoolID=tt.txtSchoolID ";
        sql = sql + "AND p.txtSchoolID=ot.txtSchoolID ";
        sql = sql + "AND ";
        //sql = sql + "  (tt.dteStart BETWEEN ot.st AND ot.et OR ";
        //sql = sql + "   tt.dteFinish BETWEEN DATEADD(s, 1, ot.st) AND ot.et) ";
        //sql = sql + "  (tt.dteStart BETWEEN ot.st AND ot.et OR ";
        //sql = sql + "   tt.dteFinish BETWEEN ot.st AND ot.et) ";

        //sql = sql + "  (ot.st BETWEEN tt.dteStart AND tt.dteFinish OR ";
        //sql = sql + "   ot.et BETWEEN tt.dteStart AND tt.dteFinish) ";
        //The above two lines amended to the following two on 23 November 2011
        sql = sql + "  (tt.dteStart BETWEEN ot.st AND ot.et OR ";
        sql = sql + "   tt.dteFinish BETWEEN ot.st AND ot.et) ";

        sql = sql + "ORDER BY tt.dteStart, name;";

        //NOW THE DISPLAY
        SqlConnection conn = new SqlConnection(ConfigurationManager.ConnectionStrings["iSAMS"].ConnectionString);
        conn.Open();
        SqlCommand cmd = new SqlCommand(sql, conn);
        SqlDataReader r = cmd.ExecuteReader();
        int linecount = 0;
        while (r.Read())
        {
            TableRow tr = new TableRow();
            TableCell tc = new TableCell();
            tc.Style.Add("width", "1.5in");
            tc.Style.Add("border", "solid");
            tc.Text = r["name"].ToString();
            tr.Cells.Add(tc);

            tc = new TableCell();
            tc.Style.Add("width", "1.5in");
            tc.Style.Add("border", "solid");
            tc.Text = r["reason"].ToString();
            tr.Cells.Add(tc);

            tc = new TableCell();
            tc.Style.Add("width", "0.75in");
            tc.Style.Add("border", "solid");
            tc.Text = r["txtSetCode"].ToString();
            tr.Cells.Add(tc);

            tc = new TableCell();
            tc.Style.Add("width", "1in");
            tc.Style.Add("border", "solid");
            tc.Text = r["date"].ToString();
            tr.Cells.Add(tc);

            tc = new TableCell();
            tc.Style.Add("width", "0.5in");
            tc.Style.Add("border", "solid");
            tc.Text = r["txtPeriodName"].ToString();
            tr.Cells.Add(tc);

            tc = new TableCell();
            tc.Style.Add("width", "0.5in");
            tc.Style.Add("border", "solid");
            tc.Text = r["clashstart"].ToString();
            tr.Cells.Add(tc);

            tc = new TableCell();
            tc.Style.Add("width", "0.5in");
            tc.Style.Add("border", "solid");
            tc.Text = r["clashend"].ToString();
            tr.Cells.Add(tc);

            abstable.Rows.Add(tr);
            linecount++;
        }
        if (linecount == 0) // there weren't any lines
        {
            TableRow ntr = new TableRow();
            TableCell ntc = new TableCell();
            ntc.Text = "There are no forthcoming absences recorded";
            ntr.Cells.Add(ntc);
            abstable.Rows.Add(ntr);
        }
        //now deal with SANI
        sql = "SELECT p.txtSurname + ', ' + p.txtForename as name, ";
        sql = sql + "sl.txtSchoolID, ";
        sql = sql + "s.txtSetCode, ";
        sql = sql + "CASE sani.txtEntryType WHEN 'home' THEN 'At home' WHEN 'ld' THEN 'Lying down' WHEN 'house' THEN 'Lying down in house' ";
        sql = sql + "WHEN 'sani' THEN 'In Sani' END as place ";
        sql = sql + "FROM ";
        sql = sql + "TblPupilManagementPupils p, ";
        sql = sql + "(SELECT s.TblTeachingManagerSetsID, s.txtSetCode, s.txtTeacher FROM TblTeachingManagerSets s ";
        sql = sql + " WHERE s.txtTeacher='" + ddlbeaks.SelectedValue + "' ";
        sql = sql + " UNION ";
        sql = sql + " SELECT s.TblTeachingManagerSetsID, s.txtSetCode, sat.txtTeacher FROM TblTeachingManagerSets s, ";
        sql = sql + " TblTeachingManagerSetAssociatedTeachers sat ";
        sql = sql + "WHERE s.TblTeachingManagerSetsID=sat.intSetID ";
        sql = sql + "AND sat.txtTeacher='" + ddlbeaks.SelectedValue + "') s, ";
        sql = sql + "TblTeachingmanagerSetLists sl, ";
        sql = sql + "mccustom.dbo.TblSani sani ";

        sql = sql + "WHERE sl.intSetID=s.TblTeachingManagerSetsID ";
        sql = sql + "AND p.txtSchoolID=sl.txtSchoolID ";
        sql = sql + "AND sani.txtSchoolID=sl.txtSchoolID ";
        sql = sql + "AND sani.txtEntryType IN ('home', 'ld', 'sani', 'house') ";
        sql = sql + "AND sani.intDeleted=0 ";
        sql = sql + "ORDER BY txtSetCode,name;";

        TableRow str = new TableRow();
        TableCell stc = new TableCell();
        stc.ColumnSpan = 5;
        stc.Text = "<hr>";
        str.Cells.Add(stc);
        abstable.Rows.Add(str);

        str = new TableRow();
        stc = new TableCell();
        stc.ColumnSpan = 5;
        string m = "The following pupil(s) you teach are currently on the sani list";
        m = m + " (as at " + DateTime.Now + ")";
        stc.Text = m;
        str.Cells.Add(stc);
        abstable.Rows.Add(str);


        SqlCommand cmdsani = new SqlCommand(sql, conn);
        SqlDataReader rsani = cmdsani.ExecuteReader();
        while (rsani.Read())
        {
            str = new TableRow();
            stc = new TableCell();
            stc.Style.Add("width", "1.5in");
            stc.Style.Add("border", "solid");
            stc.Text = rsani["name"].ToString();
            str.Cells.Add(stc);

            stc = new TableCell();
            stc.Style.Add("width", "1.5in");
            stc.Style.Add("border", "solid");
            stc.Text = rsani["place"].ToString();
            str.Cells.Add(stc);

            abstable.Rows.Add(str);

        }
        conn.Close();
    }

    private string now()
    {
        throw new NotImplementedException();
    }

    #region Control Events

    #endregion Control Events


    #region Server-Side Validation

    #endregion Server-Side Validation

    #region Error Handling

    /// <summary>
    /// Displays a critical error to the user
    /// </summary>
    /// <param name="message">Error message</param>
    private void DoCriticalError(string message)
    {
        DoCriticalError(message, true);
    }

    /// <summary>
    /// Displays a critical error to the user
    /// </summary>
    /// <param name="message">Error message</param>
    /// <param name="_bStopProcessing">Boolean to stop the processing</param>
    private void DoCriticalError(string message, bool stopProcessing)
    {
        infCriticalError.ContentText = message;
        tblCriticalError.Visible = true;
        tblMain.Visible = false;

        bStopProcessing = stopProcessing;
    }

    #endregion Error Handling

}
