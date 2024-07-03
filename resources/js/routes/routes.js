import DashboardPage from '../pages/DashboardPage.vue';
import AppValuesPage from '../pages/AppValuesPage.vue';
import ApartmentPage from '../pages/appartment_types/view.vue';
import ServicePage from '../pages/service_types/view.vue';
import EmployeesPage from '../pages/employee/EmployeesPage.vue';
import Not_Scheduled_Jobs from '../pages/Not_Scheduled_Jobs.vue';
import EmployeeDetailsPage from '../pages/EmployeeDetailsPage.vue';
import OrderServicePage from '../pages/OrderServicePage.vue';
import LoginPage from '../pages/Login.vue';
import WorkRequestsPage from '../pages/WorkRequestsPage.vue';
import LoaderSpinner from '../pages/LoaderSpinner.vue';
import JobDetailsPage from '../pages/JobDetailsPage.vue';
import PaintValues  from '../pages/paint_values/PaintValuesPage'
import Clients from '../pages/clients/clients';
import Addclient from '../pages/clients/Add';
import ClientStaffPage from "../pages/clients/staff/clientAllStaff.vue";
import PropertyStaffPage from "../pages/clients/staff/staff.vue";
import AddStaff from "../pages/clients/staff/addEdit.vue";
import AddEmployee from '../pages/employee/AddEdit'
import ClientProperty from '../pages/clients/properties/Properties';
import StaffProperty from '../pages/clients/properties/StaffProperties';
import PaintSpecs from "../pages/clients/paintspecs/PaintSpecsForm";
import PropertyPricing from "../pages/clients/properties/Pricing";
import ClientView from "../pages/clients/properties/clientPricing";
import ClientSupplier from '../pages/clients/supplier/Edit';
import RefinishSpecs from "../pages/clients/refinishspecs/RefinishSpecs";
import RefinishSpecsView from "../pages/clients/refinishspecs/RefinishSpecView";
// import ClientHistory from '../pages/clients/history/Edit';
import ClientPaintSpecs from '../pages/clients/paintspecs/Edit';
import EmployeesNotesPage from '../pages/EmployeesNotesPage.vue';
import EmpoloyeesJobDetailsPage from '../pages/employee-portal/EmpoloyeesJobDetailsPage.vue';
import Department from "../pages/department/Department";
import Invoices from "../pages/invoices/invoices";
import pastDue from "../pages/invoices/pastDue";
import PaymentAwaitingInvoices from "../pages/invoices/PaymentAwaitingInvoices";
import AddJob from "../pages/jobs/Add"
import AllJobs from "../pages/jobs/AllJobs"
import SubCategory from "../pages/service_types/sub_categories/SubCategories"
// import ViewJob from "../pages/jobs/View";
import EmptySearch from '../pages/EmptySearch.vue';
import ViewInvoice from "../pages/invoices/View";
import EmployeesTimesSheetPage from '../pages/EmployeesTimesSheetPage.vue';
import EmployeesJobListPage from '../pages/employee-portal/EmployeesJobListPage.vue';
import EmployeesTimeSheet from '../pages/employee-portal/EmployeeTimeSheetPage';
import Permissions from "../pages/permissions/permissions";
import RolePermissions from "../pages/permissions/rolePermissions";
import UserPage from "../pages/users/view.vue";
import AddUser from "../pages/users/addEdit.vue";
import RolePage from "../pages/roles/view.vue";
import RequestedJobs from "../pages/jobs/requestedJob";
import JobsCalendar from "../pages/jobs/jobCalendar";
import SubCategoryPrices from "../pages/prices/subServicePrices";
import Settings from "../pages/settings/index";
import AllPayroll from "../pages/employee/allpayroll";
import EditEmployeesTimeSheet from '../pages/employee/EditEmployeesTimeSheet';
import PayrollPrice from "../pages/department/payroll-pricing";
import clientAppartmentHistory from "../pages/clients/history/view";
import RequiredPoJobs from "../pages/jobs/requiredPoJob";
import EmployeeDocument from "../pages/employee/employee-documents/EmployeeDocument";
import Crew from "../pages/employee/crew-management/List";
import CancelledJobs from "../pages/jobs/cancelledJob";
import ClientQuote from '../pages/clients/properties/Quotes';
import RefinishValues  from '../pages/refinish_values/view';
import TaxTypes from "../pages/tax_types/TaxTypes.vue";
import PriceIncrementPage from '../pages/price_increment/view';
import ClientRevenueCalculator from "../pages/clients/Calculator/RevenueCalculator";
import CancelledInvoices from "../pages/invoices/cancelledInvoices.vue";
import ClientBilling from "../pages/clients/clientBilling.vue";

import viewCancelJob from "../pages/jobs/viewCancelJob.vue";
// import addClientNew from "../pages/clients/addClientNew.vue";
import ClientsNew from "../pages/clients/clients-new.vue";
// import addClientNew from "../pages/clients/addClientNew.vue";
import ClientPaintSpec from "../pages/clients/paintspecs/clientPaintSpecsView";
import ResetPasswordRequest from '../pages/ResetPasswordRequest.vue';
import ResetPassword from '../pages/ResetPassword.vue';
import StaffRoles from '../pages/clients/staff/staffRoles/staffRoles.vue';
import staffRolesGlobal from '../pages/staff_roles_global/view';
import allQuotes from "../pages/quotes/AllQuotes"
import creeateQuote from "../pages/quotes/Add"
import NotFound from "../pages/NotFoundPage.vue"
export const routes = [
    {name: 'dashboard', path: '/dashboard', component: DashboardPage},
    {name: 'appvalues', path: '/app-values', component: AppValuesPage},
    {name: 'department', path: '/department', component: Department},
    {name: 'paintvalues', path: '/paint-values', component: PaintValues},
    {name: 'employees', path: '/employees', component: EmployeesPage},
    {name: 'notscheduledjobs', path: '/notscheduledjobs', component: Not_Scheduled_Jobs},
    {name: 'employeedetails', path: '/employeedetails', component: EmployeeDetailsPage},
    // {name: 'invoices', path: '/invoices', component: InvoicesPage},
    {name: 'orderservice', path: '/orderservice', component: OrderServicePage},
    {name: 'login', path: '/login', component: LoginPage, meta: {
        requiresAuth: true,
      },},
    {name: 'redirectToLogin', path: '/', component: LoginPage},
    {name: 'workrequests', path: '/workrequests', component: WorkRequestsPage},
    {name: 'loader', path: '/loader', component: LoaderSpinner},
    {name: 'jobdetails', path: '/jobdetails', component: JobDetailsPage},
    {name: 'ViewCancelJob', path: '/cancel-job-details/:id', component: viewCancelJob},
    // {name: 'clients', path: '/clients', component: Clients},
    {name: 'addClient', path: '/add-client', component: Addclient},
    {name: 'clientStaff', path: '/client/:id/all-staff', component: ClientStaffPage},
    {name: 'clientAddUsers', path: '/client/:id/add-user', component: AddStaff},
    {name: 'clientViewUser', path: '/client/:id/view-user/:userId', component: AddStaff},
    {name: 'addEmployee', path: '/add-employee', component: AddEmployee},
    {name: 'viewClient', path: '/view-client/:id', component: Addclient },
    {name: 'viewEmploye', path: '/view-employe/:id', component: AddEmployee },
    {name: 'subCategory', path: '/sub-services/:id', component: SubCategory },
    {name: 'clientProperty', path: '/client-property/:id', component: ClientProperty },
    {name: 'staffProperty', path: '/staff-property', component: StaffProperty },
    {name: 'clientSupplier', path: '/client-supplier/:id', component: ClientSupplier },
    // {name: 'clientHistory', path: '/client-history/:id', component: ClientHistory },
    {name: 'clientPaintSpecs', path: '/client-paintspecs/:id', component: ClientPaintSpecs },
    {name: 'employeesNotes', path: '/employees-notes', component: EmployeesNotesPage},
    {name: 'employeesjobdetails', path: '/employees-job-details', component: EmpoloyeesJobDetailsPage},
    {name: 'paintSpecs', path: '/property-paint-specs/:id', component: PaintSpecs },
    {name: 'propertyPricing', path: '/property-pricing/:id', component: PropertyPricing },
    {name: 'clientPropertyPricing', path: '/client-property-pricing/:id', component: ClientView },
    {name: 'addJob', path: '/job-add', component: AddJob},
    {name: 'allJobs', path: '/all-jobs', component: AllJobs},
    {name: 'employeestimessheet', path: '/employeestimessheet', component: EmployeesTimesSheetPage},
    {name: 'employeesjoblist', path: '/my-jobs', component: EmployeesJobListPage},
    {name: 'employeesTimeSheet', path: '/my-time-sheet', component: EmployeesTimeSheet},
    {name: 'employeeTimeSheet', path: '/my-time-sheet-admin/:id', component: EmployeesTimeSheet},
    // {name: 'viewJob', path: '/view-job/:id', component: ViewJob },
    {name: 'viewMyJob', path: '/view-my-job/:id', component: EmpoloyeesJobDetailsPage },
    {name: 'permissions', path: '/permissions', component: Permissions},
    {name: 'rolePermissions', path: '/role-permissions', component: RolePermissions},
    {name: 'rolePermission', path: '/role-permissions/:id', component: RolePermissions},
    {name: 'editJob', path: '/edit-job/:id', component: AddJob },
    {name: 'emptySearch', path: '/emptySearch', component: EmptySearch},
    {name: 'viewInvoice', path: '/view-invoice/:id', component: ViewInvoice },
    {name: 'invoices', path: '/invoices', component: Invoices},
    {name: 'pastDue', path: '/past-due', component: pastDue},
    {name: 'paymentAwaiting', path: '/awaiting-payment', component: PaymentAwaitingInvoices},
    {name: 'apartments', path: '/apartments', component: ApartmentPage},
    {name: 'services', path: '/services', component: ServicePage},
    {name: 'users', path: '/all-users', component: UserPage},
    {name: 'addUsers', path: '/add-user', component: AddUser},
    {name: 'viewUser', path: '/view-user/:id', component: AddUser},
    {name: 'roles', path: '/all-roles', component: RolePage},
    {name: 'Job', path: '/all-jobs/:job_id', component: AllJobs},
    {name: 'requestedJobs', path: '/requested-jobs/', component: RequestedJobs},
    {name: 'cancelledJobs', path: '/cancelled-jobs/', component: CancelledJobs},
    {name: 'jobsCalendar', path: '/jobs-calendar/', component: JobsCalendar},
    {name: 'employeesjob', path: '/my-jobs/:job_id', component: EmployeesJobListPage},
    {name: 'subCategoryPrices', path: '/all-prices', component: SubCategoryPrices},
    // {name: 'ClientPrices', path: '/client-prices', component: ClientPrices},
    {name: 'settings', path: '/settings', component: Settings},
    {name: 'allPayroll', path: '/all-payroll/:id', component: AllPayroll},
    {name: 'EditEmployeesTimeSheet', path: '/edit-timesheet/:id', component: EditEmployeesTimeSheet},
    {name: 'payrollPrices', path: '/payroll-prices/:id', component: PayrollPrice},
    {name: 'clientHistory', path: '/client-history', component: clientAppartmentHistory },
    {name: 'requiredPoJobs', path: '/required-po', component: RequiredPoJobs },
    {name: 'EmployeeDocument', path: '/employee-doc/:id', component: EmployeeDocument },
    {name: 'clientQuote', path: '/client-quotes/:id', component: ClientQuote },
    {name: 'clientQuotes', path: '/client-quotes', component: ClientQuote },
    {name: 'allQuotes', path: '/all-quotes', component: allQuotes },
    {name: 'createQuote', path: '/create-quote:clientId?', component: creeateQuote},
    {name: 'editQuote', path: '/edit-quote/:id', component: creeateQuote },
    {name: 'refinishValues', path: '/refinish-values', component: RefinishValues},
    {name: 'refinishSpecs', path: '/property-refinish-specs/:id', component: RefinishSpecs },
    {name: 'refinishSpecsview', path: '/property-refinish-specs-view/:id', component: RefinishSpecsView },
    {name: 'priceIncrement', path: '/price-increment/:id', component: PriceIncrementPage},
    {name: 'TaxTypes', path: '/tax-types', component: TaxTypes},
    {name: 'revenueCalculator', path: '/client-revenue-calculator', component: ClientRevenueCalculator },
    {name: 'CancelledInvoices', path: '/cancelled-invoices', component: CancelledInvoices },
    {name: 'ClientBilling', path: '/client-billing/:id', component: ClientBilling },
    // {name: 'addClientNew', path: '/add-client-new', component: addClientNew },
    {name: 'ClientPaintSpec', path:'/property-paint-specs-view/:id', component: ClientPaintSpec},
    // {name: 'subCategoryPricesNew', path: '/all-prices-new', component: SubCategoryPricesNew},
    // {name: 'ClientPricesNew', path: '/client-prices-new/:id', component: clientPriceNew},
    {name: 'propertyStaff', path: '/property/:id/staff/', component: PropertyStaffPage},
    {name: 'crew', path: '/crews', component: Crew},
    // {name: 'ClientsNew', path: '/clients-new', component: ClientsNew},
    {name: 'ClientsNew', path: '/clients', component: ClientsNew},
    {name: 'ResetPasswordRequest', path: '/reset-password-request', component: ResetPasswordRequest},
    {name: 'ResetPassword', path: '/reset-password/:token', component: ResetPassword},
    {name: 'StaffRoles', path: '/staff-roles/:id', component: StaffRoles},
    {name: 'ManageStaffRoles', path: '/staff-roles/', component: staffRolesGlobal},
    { path: '*', component: NotFound }
];
