USE [COUNTYREVENUE]
GO
/****** Object:  StoredProcedure [dbo].[spBillHouse]    Script Date: 04-Mar-16 2:06:07 PM ******/
DROP PROCEDURE [dbo].[spBillHouse]
GO
/****** Object:  UserDefinedFunction [dbo].[fnLastHouseRecord]    Script Date: 04-Mar-16 2:06:07 PM ******/
DROP FUNCTION [dbo].[fnLastHouseRecord]
GO
/****** Object:  UserDefinedFunction [dbo].[fnLastHouseRecord]    Script Date: 04-Mar-16 2:06:07 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		<Author,,Name>
-- Create date: <Create Date,,>
-- Description:	<Description,,>
-- =============================================
CREATE FUNCTION [dbo].[fnLastHouseRecord] (@EstateID int,@HouseNumber nvarchar(50))

RETURNS @lh TABLE (LastBillNumber nvarchar(100), DateReceived datetime,Balance money) 
AS

 begin
 
	insert into @lh
	 select top 1 iif([Description]='Monthly Rent',DocumentNo,[Description])LastBillNumber,DateReceived,Balance
													from HouseReceipts 
													where EstateID=@EstateID and HouseNumber=@HouseNumber and ([Description]='Monthly Rent' or [Description] like 'Bill%')
													order by DateReceived desc
	return											
 end

GO
/****** Object:  StoredProcedure [dbo].[spBillHouse]    Script Date: 04-Mar-16 2:06:07 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[spBillHouse] 
AS

set dateformat dmy

Declare     @LastBillNumber nvarchar (100),
			@nextBillNumber nvarchar (100),
			@nextBillDate datetime,
            @LastDate date,
			@uhn nvarchar(20),
			@EstateID int,
			@HouseNumber nvarchar(50),
			@BillYear int,
			@BillMonth int,
			@BillNumber nvarchar(100),
			@DueMonths int,
			@HouseRent money,
			@RentBalance money,
			@NewBalance money,
			@Description nvarchar(100),

			@Offset int

		Declare Houses cursor For

		  select EstateID,HouseNumber,uhn from Houses order by EstateID

		OPEN Houses 
		Fetch Next From Houses Into @EstateID, @HouseNumber,@uhn

		While @@Fetch_Status = 0 Begin

			select @LastBillNumber=LastBillNumber from dbo.fnLastHouseRecord(@EstateID,@HouseNumber)
			select @LastDate=DateReceived from dbo.fnLastHouseRecord(@EstateID,@HouseNumber)
			select @RentBalance=Balance from dbo.fnLastHouseRecord(@EstateID,@HouseNumber)

			select @LastDate=convert(datetime,@LastDate,103)

			select @DueMonths=DATEDIFF(m,@LastDate,getDate())
			select @HouseRent= MonthlyRent from Tenancy where EstateID=@EstateID and HouseNumber=@HouseNumber			

			select @Offset=1
			print @LastDate
			while (@Offset<=@DueMonths)
			begin
				select @nextBillDate=DATEADD(m,@Offset,@LastDate)
				select @nextBillDate=convert(datetime,@nextBillDate,103)
				select @BillYear=year(@nextBillDate)
				select @BillMonth=month(@nextBillDate)

				select @nextBillDate=CONVERT(datetime, cast(1 as varchar)+'/'+cast(@BillMonth as varchar)+'/'+cast(@BillYear as varchar), 103)

				select @nextBillNumber='Bill '+convert(nvarchar(2),@BillMonth)+'-'+convert(nvarchar(4),@BillYear)
				select @RentBalance=@RentBalance+@HouseRent
				select @Description='Monthly Rent'

				Set dateformat dmy insert into HouseReceipts (uhn,EstateID,HouseNumber,DateReceived,[Description],DocumentNo,Amount,Balance) 
				Values(@uhn,@EstateID,@HouseNumber,@nextBillDate,@Description,@nextBillNumber,@HouseRent,@RentBalance)

				set @Offset=@Offset+1
			end

			Set dateformat dmy update Tenancy set Balance=@RentBalance where HouseNumber=@HouseNumber and EstateID=@EstateID

		Fetch Next From Houses Into @EstateID, @HouseNumber,@uhn

		End -- End of Fetch

		Close Houses
		Deallocate Houses
GO
